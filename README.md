# Passito

**Passito** is a hostel gatepass management system designed to streamline the process of issuing gate passes for students. This system allows for easy management of student entries and exits, ensuring a secure and efficient way to monitor hostel activities.

## Table of Contents

- [Passito](#passito)
  - [Table of Contents](#table-of-contents)
  - [Features](#features)
  - [Job System](#job-system)
  - [Dependencies](#dependencies)
  - [Installation](#installation)
    - [Ensure Group Ownership is Correct](#ensure-group-ownership-is-correct)
    - [Set Group-Writable Permissions](#set-group-writable-permissions)
    - [Set the SGID Bit on the Directory](#set-the-sgid-bit-on-the-directory)
    - [Verify Permissions](#verify-permissions)
    - [Setup Passito](#setup-passito)
  - [Docker Setup](#docker-setup)
  - [Storage Backends](#storage-backends)
  - [Usage](#usage)
  - [Development](#development)
  - [Contributing](#contributing)
  - [License](#license)

## Features

- User-friendly interface for students and administrators.
- Ability to issue and manage gate passes.
- View history of entries and exits.
- Notifications for upcoming passes.
- Role-based access control.
- **Asynchronous job processing** with dynamic worker scaling.
- **Health monitoring** with email alerts.
- **Auto-recovery** via systemd integration.

## Job System

Passito includes an enterprise-grade asynchronous job processing system with:

- **Dynamic Worker Scaling** - Automatically adjusts workers based on queue load
- **Health Monitoring** - Detects and alerts when workers are down
- **Auto-Recovery** - Systemd automatically restarts crashed workers
- **Email Alerts** - Admin notifications for system issues
- **Retry Logic** - Failed jobs automatically retry
- **Job Dependencies** - Chain jobs with dependency management

**📚 [Complete Job System Documentation →](docs/JOBS.md)**

Quick commands:
```bash
# Start job supervisor (production)
php passito.php jobs:supervisor

# Check queue health
php passito.php jobs:health

# View supervisor status
systemctl status passito-supervisor
```

### Scheduled Jobs (Cron -> Queue)

Cron scheduled tasks should only **dispatch jobs** into the queue. The queue worker/supervisor performs the heavy work. This keeps scheduled tasks non-blocking, improves reliability (retries/error handling), and lets you scale by running more workers.

**Requirement:** `pcntl` PHP extension must be installed/enabled for running `jobs:supervisor` (process management).

## Dependencies

To run Passito, ensure you have the following installed:

- **Sass**: For compiling stylesheets.
- **NPM**: Package manager for JavaScript.
- **Apache**: Web server to host the application.
- **PHP**: Version 8.0 or above.

Install the following dependencies
```bash
sudo apt install php php-mysql libapache2-mod-php php-xml php-mbstring php-gd php-mysql php-pcntl composer npm nodejs adminer
```

Install MySQL Database & Configure
```bash
sudo apt install mysql-server
```

Enable Adminer to manage databases
```bash
sudo a2enconf adminer
```

## Installation

### Quick Bootstrap Script

You can run a single setup script to bootstrap dependencies, directories, permissions, and optional system setup:

```bash
./setup.sh --help
./setup.sh
```

If you run `./setup.sh` with no flags, it asks interactively which steps to run.

Optional flags:

- `--full`: run the complete setup flow (apt deps + apache config + app deps + permissions)
- `--with-system`: installs OS packages via `apt` (Ubuntu/Debian)
- `--with-apache`: enables required Apache modules, writes vhost config, enables site, updates `/etc/hosts`
- `--ssl`: enables Apache SSL vhost and generates self-signed `.pem`/`-key.pem` cert files
- `--with-s3`: installs optional S3 Composer packages
- `--build`: runs production frontend build
- `--migrate`: runs DB migrations
- `--seed`: runs core seeders
- `--domain <name>`: sets Apache `ServerName` (default `passito.local`)

Example full setup:

```bash
./setup.sh --full --domain passito.local --ssl --build
```

Apache vhost template is stored at `deployment/passito.conf` and is applied by the setup script.
Apache SSL template is stored at `deployment/passito-ssl.conf`.

### Add www-data user to group for permissions

```bash
sudo usermod -aG {username} www-data
```

### Ensure Group Ownership is Correct

```bash
sudo chown -R {username}:www-data /home/{username}/htdocs/passito/storage
sudo chown -R www-data:{username} /home/{username}/htdocs/passito/storage
```

### Set Group-Writable Permissions

```bash
sudo chmod -R 775 /home/{username}/htdocs/passito/storage
```

### Set Group-Writable Permissions (Dev Mode)

```bash
sudo chown -R {username}:www-data /home/{username}/htdocs/passito/storage
sudo chmod -R 775 /home/{username}/htdocs/passito/storage
```

### Set the SGID Bit on the Directory

This ensures new files created in the storage directory inherit the group ownership of the parent directory.

```bash
sudo chmod g+s /home/{username}/htdocs/passito/storage
```

### Verify Permissions

```bash
ls -ld /home/{username}/htdocs/passito/storage/
```

Output should be:

```bash
drwxrwxr-x 3 www-data www-data 4096 Apr  6 14:00 /home/{username}/htdocs/passito/storage/
```


### Setup Passito

Follow these steps to set up Passito on your local machine:

0. **Enable Apache Modules**:
   Enable the following Apache modules for the application to work correctly:
   ```bash
   sudo a2enmod rewrite
   sudo a2enmod vhost_alias
   sudo a2enmod actions
   sudo a2enmod headers
   sudo a2enmod proxy
   sudo a2enmod proxy_wstunnel
   ```

   Add Vhost configuration for Apache:
   ```bash
   sudo nano /etc/apache2/sites-available/passito.conf
   ```
   Add the following configuration (adjust paths as necessary):
   ```apache
   <VirtualHost *:80>
      ServerAdmin webmaster@localhost
      DocumentRoot /home/henry/htdocs/passito/public
      ServerName passito.local

      <Directory /home/henry/htdocs/passito/public/>
         Options -Indexes +FollowSymLinks
         AllowOverride All
         Require all granted
      </Directory>

      <IfModule mod_deflate.c>
         AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css application/javascript application/json application/xml image/svg+xml
      </IfModule>

      <IfModule mod_expires.c>
         ExpiresActive On
         ExpiresByType image/jpg "access plus 1 year"
         ExpiresByType image/jpeg "access plus 1 year"
         ExpiresByType image/png "access plus 1 year"
         ExpiresByType image/gif "access plus 1 year"
      </IfModule>

      ServerSignature Off

      # WebSocket Proxy
      ProxyPass "/ws/" "ws://127.0.0.1:8080/"
      ProxyPassReverse "/ws/" "ws://127.0.0.1:8080/"

      ErrorLog ${APACHE_LOG_DIR}/error.log
      CustomLog ${APACHE_LOG_DIR}/access.log combined
   </VirtualHost>
   ```

   Enable the new site configuration:
   ```bash
   sudo a2ensite passito.conf
   sudo systemctl restart apache2
   ```
   Add the following line to your `/etc/hosts` file:
   ```bash
   127.0.0.1   passito.local
   ```


1. **Clone the Repository**:
   ```bash
   git clone https://github.com/henry-jacq/passito.git
   cd passito
   ```

2. **Install NPM Packages**:
   ```bash
   npm install
   ```

3. **Install Composer Dependencies** (if you haven't already):
   ```bash
   composer install
   ```

   Optional S3 support is **not installed by default**. If you want to use S3-compatible storage, install:
   ```bash
   composer require league/flysystem-aws-s3-v3 aws/aws-sdk-php
   ```

4. **Configure Environment Variables**:
   Create a `.env` file in the root of the project and configure your database and application settings. You can copy from the `.env.example` provided in the repository:
   ```bash
   cp .env.example .env
   ```

5. **Set Up Database**:
   - Create a new MySQL database for Passito.
   - Update your `.env` file with the database connection details.

6. **Run Migrations**:
   ```bash
   php passito.php migrations:migrate
   ```

7. **Run Seeders**:
   ```bash
   php passito.php app:seed app_settings
   php passito.php app:seed outpass_rules
   ```
   
8. **Setup Crontab for Scheduled Tasks**:
   - Open crontab and add scheduled job dispatchers:
   - To open crontab, run:
     ```bash
     crontab -e
     ```
   - Add the following lines to the crontab file:
      ```bash
      # Cleanup expired outpass files daily at 2 AM
      0 2 * * * /usr/bin/php /path/to/passito/passito.php app:cleanup-expired-files
      
      # Health check every 5 minutes with email alerts (optional but recommended for production)
      */5 * * * * /usr/bin/php /path/to/passito/passito.php jobs:health --send-email --exit-code-on-failure
      ```
   
   **Note:** Set `ADMIN_EMAIL` in your `.env` file to receive health alerts.
   
   **How it works:** cron executes small dispatcher commands at the scheduled time. These commands enqueue jobs, and the queue workers execute the actual work concurrently without blocking the cron process. This enables better error handling, retries for failed tasks, and horizontal scaling by increasing worker processes.

## Storage Backends

Passito supports multiple Flysystem backends via configuration.

- Default: `local` storage (works out of the box).
- Optional: `s3` / S3-compatible object storage.

Set these environment variables when using S3:

```env
STORAGE_DRIVER=s3
AWS_DEFAULT_REGION=us-east-1
AWS_VERSION=latest
AWS_BUCKET=your-bucket
AWS_ENDPOINT=
AWS_USE_PATH_STYLE_ENDPOINT=false
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_PREFIX=
```

Notes:

- If `STORAGE_DRIVER` is not set, Passito uses local storage.
- For S3 mode, install these packages first:
  - `league/flysystem-aws-s3-v3`
  - `aws/aws-sdk-php`

9. **Start the Job Supervisor** (Required for processing jobs):
   ```bash
   php passito.php jobs:supervisor
   ```
   
   For production, use systemd or supervisor to keep it running:
   ```bash
   sudo cp deployment/passito-supervisor.service /etc/systemd/system/
   sudo systemctl enable passito-supervisor
   sudo systemctl start passito-supervisor
   ```

10. **Start the Development Server**:
   - Make sure Apache is running and configured to serve from the `public` directory.
   - Alternatively, you can use Vite for the front-end development:
     ```bash
     npm run dev
     ```

## Docker Setup

To set up Passito using Docker, follow these steps:

1. **Install Docker**: Ensure you have [Docker](https://www.docker.com/get-started) and [Docker Compose](https://docs.docker.com/compose/install/) installed on your machine.

2. **Build and Start the Containers**:
   From the root of your project, run:
   ```bash
   docker-compose up --build
   ```
   This will build the containers defined in your `docker-compose.yml` file.

3. **Access the Application**:
   Open your web browser and go to `http://localhost:8000` (or the port you have configured in the `docker-compose.yml`).

4. **Access Adminer** (optional):
   If you want to manage your database using Adminer, you can access it at `http://localhost:8080`.

5. **Running Vite**:
   If you want to run the Vite development server for frontend resources, execute the following command in the Docker container:
   ```bash
   npm run dev
   ```
   Ensure to adjust the configuration in `vite.config.js` to allow external access if necessary.

## Usage

1. **Access the Application**:
   Open your web browser and go to `http://localhost:8000` (or the port you have configured in Apache).

2. **Login**:
   Use the admin credentials to log in or register as a new user.

3. **Manage Gate Passes**:
   - Issue new gate passes.
   - View and manage existing passes.
   - Check the history of entries and exits.

## Development

To contribute to the development of Passito:

1. **Create a new branch**:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes**.

3. **Commit your changes**:
   ```bash
   git commit -m "Add your commit message"
   ```

4. **Push to the branch**:
   ```bash
   git push origin feature/your-feature-name
   ```

5. **Create a Pull Request** on GitHub.

## Contributing

Contributions are welcome! Please feel free to submit issues or pull requests to improve the project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
