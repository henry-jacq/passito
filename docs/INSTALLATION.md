# Installation Guide

This guide provides detailed instructions for installing Passito in both development and production environments.

## System Requirements

### Minimum Requirements

- **PHP**: 8.0 or higher
- **MySQL**: 5.7 or higher / MariaDB 10.3 or higher
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Node.js**: 16.x or higher
- **Composer**: 2.x
- **NPM**: 8.x or higher

### PHP Extensions

Required PHP extensions:

```bash
php-mysql
php-xml
php-mbstring
php-gd
php-curl
php-zip
php-intl
php-json
php-pcntl  # For job workers
```

### System Dependencies

```bash
# Ubuntu/Debian
sudo apt install php php-mysql libapache2-mod-php php-xml php-mbstring \
  php-gd php-curl php-zip php-intl composer npm nodejs mysql-server

# CentOS/RHEL
sudo yum install php php-mysqlnd php-xml php-mbstring php-gd \
  php-curl php-zip php-intl composer npm nodejs mysql-server
```

## Installation Methods

### Method 0: Bootstrap Script (Recommended for Local Setup)

Passito includes `setup.sh` to automate common setup steps.

```bash
./setup.sh --help
./setup.sh
```

When no flags are passed, the script runs in interactive mode and asks what to configure.

Optional flags:

- `--full` to run full setup flow
- `--with-system` to install apt packages
- `--with-apache` to enable Apache modules and configure vhost + hosts entry
- `--ssl` to configure Apache SSL vhost and generate self-signed cert files
- `--with-s3` to install optional S3 dependencies
- `--build` to build frontend assets
- `--migrate` to run migrations
- `--seed` to run core seeders
- `--domain <name>` to set Apache ServerName

Apache vhost template used by the script: `deployment/passito.conf`
Apache SSL vhost template used by the script: `deployment/passito-ssl.conf`

### Method 1: Standard Installation

#### Step 1: Clone Repository

```bash
git clone https://github.com/henry-jacq/passito.git
cd passito
```

#### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

#### Step 3: Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Edit configuration
nano .env
```

Required environment variables:

```bash
# Application
APP_NAME=Passito
APP_URL=https://passito.local
APP_ENV=production
APP_DEBUG=false

# Database
DB_HOST=localhost
DB_PORT=3306
DB_NAME=passito
DB_USER=passito_user
DB_PASS=secure_password

# SMTP Email
SMTP_HOST=smtp.gmail.com
SMTP_USER=your-email@gmail.com
SMTP_PASS=your-app-password
SMTP_PORT=587
MAILER_FROM=noreply@passito.local

# Admin Alerts
ADMIN_EMAIL=admin@example.com

# JWT
JWT_SECRET=generate-random-32-char-string
JWT_TTL=3600

# Twilio (Optional)
TWILIO_SID=your-twilio-sid
TWILIO_AUTH_TOKEN=your-twilio-token
TWILIO_FROM=+1234567890
```

#### Step 4: Database Setup

```bash
# Create database
mysql -u root -p
```

```sql
CREATE DATABASE passito CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'passito_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON passito.* TO 'passito_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

```bash
# Run migrations
php passito.php migrations:migrate

# Seed initial data
php passito.php app:seed app_settings
php passito.php app:seed outpass_rules
```

#### Step 5: File Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data public/

# Set permissions
sudo chmod -R 775 storage/
sudo chmod -R 755 public/

# Set SGID bit for group inheritance
sudo chmod g+s storage/
```

#### Step 6: Web Server Configuration

**Apache Configuration**:

```bash
sudo nano /etc/apache2/sites-available/passito.conf
```

```apache
<VirtualHost *:80>
    ServerName passito.local
    ServerAdmin admin@passito.local
    DocumentRoot /var/www/passito/public

    <Directory /var/www/passito/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # WebSocket Proxy
    ProxyPass "/ws/" "ws://127.0.0.1:8080/"
    ProxyPassReverse "/ws/" "ws://127.0.0.1:8080/"

    # Compression
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css
        AddOutputFilterByType DEFLATE application/javascript application/json
    </IfModule>

    # Caching
    <IfModule mod_expires.c>
        ExpiresActive On
        ExpiresByType image/jpg "access plus 1 year"
        ExpiresByType image/jpeg "access plus 1 year"
        ExpiresByType image/png "access plus 1 year"
        ExpiresByType text/css "access plus 1 month"
        ExpiresByType application/javascript "access plus 1 month"
    </IfModule>

    ErrorLog ${APACHE_LOG_DIR}/passito-error.log
    CustomLog ${APACHE_LOG_DIR}/passito-access.log combined
</VirtualHost>
```

Enable required modules and site:

```bash
sudo a2enmod rewrite headers proxy proxy_wstunnel deflate expires
sudo a2ensite passito.conf
sudo systemctl restart apache2
```

**Nginx Configuration**:

```bash
sudo nano /etc/nginx/sites-available/passito
```

```nginx
server {
    listen 80;
    server_name passito.local;
    root /var/www/passito/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # WebSocket proxy
    location /ws/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable site:

```bash
sudo ln -s /etc/nginx/sites-available/passito /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### Step 7: Job Supervisor Setup

```bash
# Copy systemd service
sudo cp deployment/passito-supervisor.service /etc/systemd/system/

# Edit paths if needed
sudo nano /etc/systemd/system/passito-supervisor.service

# Enable and start
sudo systemctl daemon-reload
sudo systemctl enable passito-supervisor
sudo systemctl start passito-supervisor

# Check status
sudo systemctl status passito-supervisor
```

#### Step 8: Scheduled Tasks

```bash
# Edit crontab
crontab -e
```

Add these lines:

```bash
# Cleanup expired files daily at 2 AM
0 2 * * * /usr/bin/php /var/www/passito/passito.php app:cleanup-expired-files

# Dispatch due automated report emails every minute
* * * * * /usr/bin/php /var/www/passito/passito.php app:dispatch-scheduled-reports

# Health check every 5 minutes
*/5 * * * * /usr/bin/php /var/www/passito/passito.php jobs:health --send-email --exit-code-on-failure
```

For automated reports, super admins are always included as recipients by default.  
In report settings, select only additional wardens.

#### Step 9: Build Assets

```bash
# Production build
npm run build

# Or development with hot reload
npm run dev
```

#### Step 10: Create Super Admin

```bash
php passito.php app:create-super-admin
```

Follow the prompts to create your admin account.

#### Step 11: Data Backup and Reset Commands

Use these maintenance commands as needed:

```bash
# Backup database + files (includes storage/ by default)
php passito.php app:backup-data

# Restore from backup directory or zip (destructive)
php passito.php app:import-backup /path/to/backup --force

# Factory reset app data (destructive, also clears storage runtime files)
php passito.php app:factory-reset --force
```

Optional flags:
- `app:backup-data --no-db --no-files --source=storage --source=resources/assets --no-zip`
- `app:import-backup /path/to/backup --force --no-files`
- `app:factory-reset --force --drop-super-admins --keep-reference --no-reseed`

### Method 2: Docker Installation

#### Step 1: Prerequisites

```bash
# Install Docker and Docker Compose
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

#### Step 2: Clone and Configure

```bash
git clone https://github.com/henry-jacq/passito.git
cd passito
cp .env.example .env
```

Edit `.env` for Docker:

```bash
DB_HOST=db
DB_PORT=3306
DB_NAME=passito
DB_USER=passito
DB_PASS=passito_password
```

#### Step 3: Build and Start

```bash
docker-compose up -d --build
```

#### Step 4: Initialize Database

```bash
# Run migrations
docker-compose exec web php passito.php migrations:migrate

# Seed data
docker-compose exec web php passito.php app:seed app_settings
docker-compose exec web php passito.php app:seed outpass_rules

# Create admin
docker-compose exec web php passito.php app:create-super-admin
```

#### Step 5: Access Application

- Application: http://localhost:8000
- Adminer: http://localhost:8080

## Post-Installation

### Verify Installation

```bash
# Check PHP version
php -v

# Check required extensions
php -m | grep -E 'mysql|xml|mbstring|gd|curl|zip|intl|pcntl'

# Check database connection
php passito.php migrations:status

# Check job supervisor
sudo systemctl status passito-supervisor

# Check web server
curl -I http://passito.local
```

### Security Hardening

#### 1. Disable Debug Mode

```bash
# .env
APP_DEBUG=false
APP_ENV=production
```

#### 2. Secure File Permissions

```bash
# Restrict .env file
chmod 600 .env

# Restrict config files
chmod 644 config/*.php

# Ensure storage is writable
chmod 775 storage/
```

#### 3. Configure Firewall

```bash
# UFW (Ubuntu)
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow 22/tcp
sudo ufw enable
```

#### 4. SSL Certificate

```bash
# Using Let's Encrypt
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d passito.yourdomain.com
```

#### 5. Database Security

```sql
-- Remove test databases
DROP DATABASE IF EXISTS test;

-- Restrict user privileges
REVOKE ALL PRIVILEGES ON *.* FROM 'passito_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON passito.* TO 'passito_user'@'localhost';
FLUSH PRIVILEGES;
```

### Performance Tuning

#### PHP Configuration

```bash
sudo nano /etc/php/8.0/apache2/php.ini
```

```ini
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 60
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 10000
```

#### MySQL Configuration

```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

```ini
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
max_connections = 200
query_cache_size = 64M
```

#### Apache Configuration

```bash
sudo nano /etc/apache2/mods-available/mpm_prefork.conf
```

```apache
<IfModule mpm_prefork_module>
    StartServers 5
    MinSpareServers 5
    MaxSpareServers 10
    MaxRequestWorkers 150
    MaxConnectionsPerChild 3000
</IfModule>
```

## Troubleshooting

### Common Issues

#### Database Connection Failed

```bash
# Check MySQL is running
sudo systemctl status mysql

# Test connection
mysql -u passito_user -p passito

# Check credentials in .env
grep DB_ .env
```

#### Permission Denied on Storage

```bash
# Fix ownership
sudo chown -R www-data:www-data storage/

# Fix permissions
sudo chmod -R 775 storage/
sudo chmod g+s storage/
```

#### Job Workers Not Processing

```bash
# Check supervisor status
sudo systemctl status passito-supervisor

# Check worker logs
sudo journalctl -u passito-supervisor -f

# Restart supervisor
sudo systemctl restart passito-supervisor
```

#### 500 Internal Server Error

```bash
# Check Apache error log
sudo tail -f /var/log/apache2/passito-error.log

# Check PHP error log
sudo tail -f /var/log/php8.0-fpm.log

# Enable debug mode temporarily
# .env: APP_DEBUG=true
```

#### Assets Not Loading

```bash
# Rebuild assets
npm run build

# Check file permissions
ls -la public/build/

# Clear browser cache
```

### Getting Help

If you encounter issues not covered here:

1. Check the [FAQ](FAQ.md)
2. Search [GitHub Issues](https://github.com/henry-jacq/passito/issues)
3. Review application logs in `storage/logs/`
4. Enable debug mode and check error messages
5. Create a new issue with detailed information

## Next Steps

After successful installation:

1. Read the [Configuration Guide](CONFIGURATION.md)
2. Follow the [Quick Start Guide](QUICK_START.md)
3. Review the [Admin Guide](ADMIN_GUIDE.md)
4. Configure [Health Monitoring](JOB_HEALTH_MONITORING.md)
5. Set up [Backups](DEPLOYMENT.md#backup-strategy)

## Upgrading

For upgrade instructions, see the [Deployment Guide](DEPLOYMENT.md#upgrading).
