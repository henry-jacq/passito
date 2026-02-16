#!/usr/bin/env bash
set -euo pipefail

# Project: Passito - Hostel Outpass Management System
# Script: setup.sh
# Description: Bootstrap the full application environment.

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT_DIR"

INSTALL_SYSTEM_DEPS=0
ENABLE_APACHE_MODULES=0
INSTALL_S3_DEPS=0
RUN_BUILD=0
RUN_MIGRATIONS=0
RUN_SEEDERS=0
FULL_SETUP=0
ENABLE_SSL=0
SERVER_NAME="passito.local"
ORIGINAL_ARGC=$#

print_help() {
  cat <<'EOF'
Usage: ./setup.sh [options]

Options:
  --full                Run system deps + apache config + app deps + permissions
  --with-system         Install required apt packages (Ubuntu/Debian)
  --with-apache         Enable Apache modules + configure vhost + /etc/hosts entry
  --ssl                 Configure SSL Apache vhost and generate self-signed certs
  --with-s3             Install optional S3 Composer packages
  --build               Run frontend production build (npm run build)
  --migrate             Run database migrations
  --seed                Run application seeders (same flow as populate.sh, non-interactive)
  --domain <name>       Apache ServerName (default: passito.local)
  -h, --help            Show this help

Examples:
  ./setup.sh
  ./setup.sh --full --domain passito.local --ssl
  ./setup.sh --with-system --with-apache --build
  ./setup.sh --migrate --seed

Note:
  Apache is the only web server auto-configured by this setup script.
EOF
}

log() {
  printf '[setup] %s\n' "$1"
}

has_cmd() {
  command -v "$1" >/dev/null 2>&1
}

prompt_yes_no() {
  local prompt="$1"
  local default="${2:-n}"
  local answer

  if [[ "$default" == "y" ]]; then
    read -r -p "$prompt [Y/n]: " answer
    answer="${answer:-y}"
  else
    read -r -p "$prompt [y/N]: " answer
    answer="${answer:-n}"
  fi

  answer="${answer,,}"
  [[ "$answer" == "y" || "$answer" == "yes" ]]
}

escape_sed_replacement() {
  printf '%s' "$1" | sed -e 's/[\/&]/\\&/g'
}

configure_apache() {
  local template_file="${ROOT_DIR}/deployment/passito.conf"
  local vhost_file="/etc/apache2/sites-available/passito.conf"
  local ssl_template_file="${ROOT_DIR}/deployment/passito-ssl.conf"
  local ssl_vhost_file="/etc/apache2/sites-available/passito-ssl.conf"

  if [[ ! -f "$template_file" ]]; then
    log "Apache template not found: $template_file"
    exit 1
  fi

  log "Enabling Apache modules..."
  sudo a2enmod rewrite vhost_alias actions headers proxy proxy_wstunnel deflate expires
  if [[ $ENABLE_SSL -eq 1 ]]; then
    sudo a2enmod ssl
  fi

  log "Writing Apache vhost from template: $template_file -> $vhost_file"
  local doc_root
  local doc_root_escaped
  local server_name_escaped
  doc_root="${ROOT_DIR}/public"
  doc_root_escaped="$(escape_sed_replacement "$doc_root")"
  server_name_escaped="$(escape_sed_replacement "$SERVER_NAME")"

  sudo sed \
    -e "s/__DOCUMENT_ROOT__/${doc_root_escaped}/g" \
    -e "s/__SERVER_NAME__/${server_name_escaped}/g" \
    "$template_file" | sudo tee "$vhost_file" >/dev/null

  log "Enabling site configuration..."
  sudo a2ensite passito.conf
  if [[ $ENABLE_SSL -eq 1 ]]; then
    if ! has_cmd openssl; then
      log "openssl not found. Install it or run setup with --with-system."
      exit 1
    fi

    if [[ ! -f "$ssl_template_file" ]]; then
      log "Apache SSL template not found: $ssl_template_file"
      exit 1
    fi

    local cert_base cert_file key_file cert_file_escaped key_file_escaped safe_server_name
    safe_server_name="$(printf '%s' "$SERVER_NAME" | tr '/ ' '__')"
    cert_base="${ROOT_DIR}/${safe_server_name}"
    cert_file="${cert_base}.pem"
    key_file="${cert_base}-key.pem"

    if [[ ! -f "$cert_file" || ! -f "$key_file" ]]; then
      log "Generating self-signed SSL certificate for ${SERVER_NAME}..."
      openssl req -x509 -nodes -newkey rsa:2048 -sha256 -days 365 \
        -keyout "$key_file" \
        -out "$cert_file" \
        -subj "/CN=${SERVER_NAME}" >/dev/null 2>&1
    else
      log "SSL cert and key already exist, skipping generation."
    fi

    cert_file_escaped="$(escape_sed_replacement "$cert_file")"
    key_file_escaped="$(escape_sed_replacement "$key_file")"

    log "Writing Apache SSL vhost: $ssl_vhost_file"
    sudo sed \
      -e "s/__DOCUMENT_ROOT__/${doc_root_escaped}/g" \
      -e "s/__SERVER_NAME__/${server_name_escaped}/g" \
      -e "s/__SSL_CERT_FILE__/${cert_file_escaped}/g" \
      -e "s/__SSL_KEY_FILE__/${key_file_escaped}/g" \
      "$ssl_template_file" | sudo tee "$ssl_vhost_file" >/dev/null

    sudo a2ensite passito-ssl.conf
  fi
  sudo a2enconf adminer || true

  if ! grep -qE "[[:space:]]${SERVER_NAME}([[:space:]]|\$)" /etc/hosts; then
    log "Adding ${SERVER_NAME} to /etc/hosts..."
    echo "127.0.0.1 ${SERVER_NAME}" | sudo tee -a /etc/hosts >/dev/null
  else
    log "/etc/hosts already contains ${SERVER_NAME}, skipping."
  fi

  log "Restarting Apache..."
  sudo systemctl restart apache2
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --full) FULL_SETUP=1 ;;
    --with-system) INSTALL_SYSTEM_DEPS=1 ;;
    --with-apache) ENABLE_APACHE_MODULES=1 ;;
    --ssl) ENABLE_SSL=1 ;;
    --with-s3) INSTALL_S3_DEPS=1 ;;
    --build) RUN_BUILD=1 ;;
    --migrate) RUN_MIGRATIONS=1 ;;
    --seed) RUN_SEEDERS=1 ;;
    --domain)
      shift
      SERVER_NAME="${1:-}"
      if [[ -z "$SERVER_NAME" ]]; then
        echo "Missing value for --domain"
        exit 1
      fi
      ;;
    -h|--help) print_help; exit 0 ;;
    *)
      printf 'Unknown option: %s\n\n' "$1"
      print_help
      exit 1
      ;;
  esac
  shift
done

if [[ $ORIGINAL_ARGC -eq 0 ]]; then
  log "No flags passed. Entering interactive setup mode."
  echo

  if prompt_yes_no "Run full setup (system deps + apache + app deps + permissions)?" "y"; then
    FULL_SETUP=1
  else
    if prompt_yes_no "Install system dependencies via apt?"; then
      INSTALL_SYSTEM_DEPS=1
    fi
    if prompt_yes_no "Configure Apache (modules, vhost, hosts entry)?"; then
      ENABLE_APACHE_MODULES=1
    fi
  fi

  if [[ $ENABLE_APACHE_MODULES -eq 1 || $FULL_SETUP -eq 1 ]]; then
    read -r -p "Apache ServerName [${SERVER_NAME}]: " custom_domain
    if [[ -n "${custom_domain:-}" ]]; then
      SERVER_NAME="$custom_domain"
    fi
    if prompt_yes_no "Enable SSL (self-signed cert + Apache SSL vhost)?"; then
      ENABLE_SSL=1
    fi
  fi

  if prompt_yes_no "Install optional S3 PHP packages?"; then
    INSTALL_S3_DEPS=1
  fi
  if prompt_yes_no "Run frontend production build?"; then
    RUN_BUILD=1
  fi
  if prompt_yes_no "Run database migrations now?"; then
    RUN_MIGRATIONS=1
  fi
  if prompt_yes_no "Run core seeders now?"; then
    RUN_SEEDERS=1
  fi

  echo
fi

if [[ $FULL_SETUP -eq 1 ]]; then
  INSTALL_SYSTEM_DEPS=1
  ENABLE_APACHE_MODULES=1
fi

if [[ $ENABLE_SSL -eq 1 ]]; then
  ENABLE_APACHE_MODULES=1
fi

if [[ $INSTALL_SYSTEM_DEPS -eq 1 ]]; then
  log "Installing system dependencies (apt)..."
  sudo apt update
  sudo apt install -y \
    php php-mysql libapache2-mod-php php-xml php-mbstring php-gd php-curl \
    php-zip php-intl php-pcntl composer npm nodejs mysql-server adminer openssl
fi

if [[ $ENABLE_APACHE_MODULES -eq 1 ]]; then
  configure_apache
fi

if [[ ! -f ".env" ]]; then
  log "Creating .env from .env.example..."
  cp .env.example .env
else
  log ".env already exists, skipping copy."
fi

if ! has_cmd composer; then
  log "Composer not found. Install dependencies manually or run with --with-system."
  exit 1
fi

if ! has_cmd npm; then
  log "npm not found. Install dependencies manually or run with --with-system."
  exit 1
fi

log "Installing Composer dependencies..."
composer install

if [[ $INSTALL_S3_DEPS -eq 1 ]]; then
  log "Installing optional S3 dependencies..."
  composer require league/flysystem-aws-s3-v3 aws/aws-sdk-php
fi

log "Installing Node dependencies..."
npm install

if [[ $RUN_BUILD -eq 1 ]]; then
  log "Building frontend assets..."
  npm run build
fi

log "Ensuring required directories exist..."
mkdir -p storage/cache/doctrine storage/jobs storage/uploads public/build

CURRENT_USER="${SUDO_USER:-$USER}"
CURRENT_GROUP="$(id -gn "$CURRENT_USER")"

log "Adding www-data user to ${CURRENT_USER} group..."
sudo usermod -aG "$CURRENT_USER" www-data || true

log "Setting storage/public permissions and group ownership..."
sudo chown -R "$CURRENT_USER":www-data storage public || true
sudo chown -R www-data:"$CURRENT_GROUP" storage || true
sudo chmod -R 775 storage
sudo chmod g+s storage
sudo chmod -R 755 public

if [[ $RUN_MIGRATIONS -eq 1 ]]; then
  log "Running migrations..."
  php passito.php migrations:migrate
fi

if [[ $RUN_SEEDERS -eq 1 ]]; then
  log "Running core seeders..."
  php passito.php app:seed app_settings
  php passito.php app:seed outpass_templates
  php passito.php app:seed report_configs
fi

log "Setup completed."
echo
echo "Next steps:"
echo "  1) Configure .env (DB, SMTP, JWT_SECRET, LINK_SECRET)"
echo "  2) Run migrations: php passito.php migrations:migrate"
echo "  3) Populate data: ./populate.sh"
echo "  4) Start app: npm run dev (and Apache/web server)"
