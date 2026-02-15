# Passito Documentation

Complete technical documentation for the Passito Hostel Gatepass Management System.

## Documentation Structure

### Core Documentation

1. **[Installation Guide](INSTALLATION.md)**
   - System requirements
   - Installation methods (Standard, Docker)
   - Configuration
   - Troubleshooting

2. **[Architecture](ARCHITECTURE.md)**
   - System architecture overview
   - Design patterns
   - Component structure
   - Technology stack

3. **[Database Schema](DATABASE.md)**
   - Entity relationship diagrams
   - Table structures
   - Relationships and constraints
   - Migration guide

4. **[API Reference](API.md)**
   - Authentication
   - Endpoints documentation
   - Request/response formats
   - Error handling

### Job System Documentation

5. **[Job System Guide](JOBS.md)**
   - Complete job processing system
   - Dynamic worker scaling
   - Health monitoring & alerts
   - Production deployment
   - Troubleshooting

## Quick Links

### Getting Started
- [Installation →](INSTALLATION.md)
- [Configuration →](INSTALLATION.md#configuration)
- [First Run →](INSTALLATION.md#first-run)

### Development
- [Architecture Overview →](ARCHITECTURE.md)
- [Database Schema →](DATABASE.md)
- [API Endpoints →](API.md)

### Operations
- [Job System Setup →](JOBS.md#production-setup)
- [Health Monitoring →](JOBS.md#health-monitoring)
- [Troubleshooting →](JOBS.md#troubleshooting)

## System Overview

Passito is a comprehensive hostel gatepass management system built with:

- **Backend**: PHP 8.0+, Slim Framework, Doctrine ORM
- **Frontend**: Vanilla JavaScript, TailwindCSS
- **Database**: MySQL/MariaDB
- **Job Queue**: Custom asynchronous processing system
- **Email**: PHPMailer with SMTP
- **SMS**: Twilio integration

### Key Features

- Role-based access control (Super Admin, Admin, Student, Verifier)
- Digital outpass generation with QR codes
- Parent verification system
- Real-time notifications
- Asynchronous job processing
- Health monitoring with email alerts
- Auto-scaling worker management

## Common Tasks

### Development

```bash
# Start development server
npm run dev

# Run database migrations
php passito.php migrations:migrate

# Seed database
php passito.php app:seed app_settings
```

### Production

```bash
# Start job supervisor
systemctl start passito-supervisor

# Check system health
php passito.php jobs:health

# View logs
journalctl -u passito-supervisor -f

## Scheduled Jobs (Cron -> Queue)

Cron scheduled tasks should only dispatch jobs into the queue. The queue worker/supervisor performs the heavy work. This keeps scheduled tasks non-blocking, allows multiple tasks to be processed concurrently, and improves reliability with better error handling and retries. Scaling is done by increasing worker processes (or running the supervisor with higher max workers).

**Note:** `pcntl` PHP extension must be installed/enabled to run `php passito.php jobs:supervisor`.
```

## Support

For issues or questions:

1. Check the relevant documentation section
2. Review [Troubleshooting](JOBS.md#troubleshooting)
3. Check system logs
4. Review error messages in the database

## Contributing

When contributing to documentation:

1. Keep language clear and concise
2. Include code examples where appropriate
3. Update the table of contents
4. Test all commands and code snippets
5. Follow the existing structure and style

## Version

Documentation version: 0.7.0  
Last updated: February 2026
