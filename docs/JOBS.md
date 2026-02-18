# Job System Guide

Complete guide to Passito's asynchronous job processing system with dynamic worker scaling and health monitoring.

## Table of Contents

- [Overview](#overview)
- [Architecture](#architecture)
- [Quick Start](#quick-start)
- [Production Setup](#production-setup)
- [Worker Management](#worker-management)
- [Health Monitoring](#health-monitoring)
- [Creating Jobs](#creating-jobs)
- [Scheduled Tasks](#scheduled-tasks)
- [Monitoring & Debugging](#monitoring--debugging)
- [Troubleshooting](#troubleshooting)
- [Performance Tuning](#performance-tuning)
- [Command Reference](#command-reference)

## Overview

The job system provides enterprise-grade asynchronous processing with:

- **Dynamic Scaling**: Automatically adjusts worker count based on queue load
- **Health Monitoring**: Detects and alerts when workers are down
- **Auto-Recovery**: Systemd integration for automatic restart
- **Retry Logic**: Failed jobs automatically retry (up to 3 attempts)
- **Dependencies**: Chain jobs with dependency management
- **Email Alerts**: Admin notifications for system issues

### System Architecture

```
User Action / Cron → Job Dispatcher → Job Queue (Database)
                                           ↓
                                    Job Supervisor
                                           ↓
                        ┌──────────────────┼──────────────────┐
                        ↓                  ↓                  ↓
                    Worker 1           Worker 2           Worker N
                        ↓                  ↓                  ↓
                   Job Execution     Job Execution     Job Execution

Parallel: Health Monitor → Email Alerts → Systemd Auto-Restart
```

## Quick Start

### Development

Start a single worker for testing:

```bash
php passito.php jobs:worker
```

### Production

Start the supervisor (manages workers automatically):

```bash
php passito.php jobs:supervisor
```

Or use systemd:

```bash
sudo systemctl start passito-supervisor
```

## Production Setup

### Step 1: Install Systemd Service

```bash
# Copy service file
sudo cp deployment/passito-supervisor.service /etc/systemd/system/

# Enable auto-start on boot
sudo systemctl enable passito-supervisor

# Start the service
sudo systemctl start passito-supervisor

# Check status
sudo systemctl status passito-supervisor
```

### Step 2: Configure Email Alerts

Add admin email to `.env`:

```bash
ADMIN_EMAIL=admin@example.com
```

### Step 3: Setup Cron Jobs

```bash
crontab -e
```

Add these lines:

```bash
# Cleanup expired files daily at 2 AM
0 2 * * * /usr/bin/php /path/to/passito/passito.php app:cleanup-expired-files

# Dispatch due automated report emails every minute
* * * * * /usr/bin/php /path/to/passito/passito.php app:dispatch-scheduled-reports

# Health check every 5 minutes with email alerts
*/5 * * * * /usr/bin/php /path/to/passito/passito.php jobs:health --send-email --exit-code-on-failure
```

Note for automated reports:
- Super admins are always included automatically as recipients.
- The report settings recipient checklist is for additional wardens only.

### Step 4: Verify Setup

```bash
# Check supervisor is running
systemctl status passito-supervisor

# Check workers are spawned
ps aux | grep jobs:worker

# Check queue health
php passito.php jobs:health
```

## Worker Management

### Supervisor Command

The supervisor monitors queue load and dynamically spawns/terminates workers.

**Basic usage:**

```bash
php passito.php jobs:supervisor
```

**With options:**

```bash
php passito.php jobs:supervisor \
  --min-workers=2 \
  --max-workers=20 \
  --scale-up-threshold=10 \
  --scale-down-threshold=3 \
  --check-interval=10
```

**Options:**

| Option | Default | Description |
|--------|---------|-------------|
| `--min-workers` | 1 | Minimum workers to maintain |
| `--max-workers` | 10 | Maximum workers allowed |
| `--scale-up-threshold` | 5 | Jobs per worker to trigger scale up |
| `--scale-down-threshold` | 2 | Jobs per worker to trigger scale down |
| `--check-interval` | 10 | Seconds between load checks |

### Scaling Logic

**Scale Up**: When `pending_jobs / active_workers > scale_up_threshold`

Example: 50 jobs ÷ 5 workers = 10 jobs/worker → Scale up

**Scale Down**: When `pending_jobs / active_workers < scale_down_threshold`

Example: 5 jobs ÷ 5 workers = 1 job/worker → Scale down

### Worker Command

For manual control or development:

```bash
php passito.php jobs:worker
```

This starts a single worker that processes jobs until stopped (Ctrl+C).

## Health Monitoring

### Health Check Command

Monitor queue health and detect issues:

```bash
php passito.php jobs:health
```

**Output:**

```
Job Queue Health Check
==================================================
Pending Jobs:    12
Processing Jobs: 2
Failed Jobs:     1
Stale Jobs:      0 (older than 5m)

✓ Job queue is healthy
```

### Email Alerts

Enable email alerts for unhealthy queues:

```bash
php passito.php jobs:health --send-email
```

**Alert triggers:**

- Stale jobs (pending > threshold minutes)
- Stuck processing jobs (workers crashed)
- High failure rate (> 10%)

**Email content includes:**

- Current metrics table
- Possible causes
- Recommended actions
- Color-coded status indicators

### Health Check Options

```bash
--send-email              # Send email if unhealthy
--exit-code-on-failure    # Exit with code 1 if unhealthy (for monitoring)
--alert-threshold=5       # Minutes before alerting on stale jobs
```

### Automated Monitoring

Add to cron for continuous monitoring:

```bash
*/5 * * * * php passito.php jobs:health --send-email --exit-code-on-failure
```

## Creating Jobs

### Step 1: Create Job Class

```php
// app/Jobs/MyCustomJob.php
namespace App\Jobs;

use App\Interfaces\JobInterface;

class MyCustomJob implements JobInterface
{
    public function __construct(
        private readonly MyService $myService
    ) {}

    public function handle(array $payload): ?array
    {
        $id = $payload['id'];
        
        // Perform work
        $result = $this->myService->doSomething($id);
        
        // Return result (optional)
        return [
            'status' => 'completed',
            'result' => $result
        ];
    }
}
```

### Step 2: Dispatch Job

```php
use App\Core\JobDispatcher;
use App\Core\JobPayloadBuilder;

$payload = new JobPayloadBuilder();
$payload->set('id', 123);

$job = $jobDispatcher->dispatch(
    MyCustomJob::class,
    $payload
);
```

### Job with Delay

```php
$availableAt = new \DateTimeImmutable('+5 minutes');

$job = $jobDispatcher->dispatch(
    SendReminderEmail::class,
    $payload,
    $availableAt
);
```

### Job with Dependencies

```php
// First job
$qrJob = $jobDispatcher->dispatch(
    GenerateQrCode::class,
    (new JobPayloadBuilder())->set('outpass_id', 123)
);

// Second job depends on first
$pdfJob = $jobDispatcher->dispatch(
    GenerateOutpassPdf::class,
    (new JobPayloadBuilder())
        ->set('outpass_id', 123)
        ->dependsOn($qrJob->getId())
);

// Third job depends on second
$emailJob = $jobDispatcher->dispatch(
    SendOutpassEmail::class,
    (new JobPayloadBuilder())
        ->set('outpass_id', 123)
        ->dependsOn($pdfJob->getId())
);
```

## Scheduled Tasks

### Pattern: Cron → Command → Job → Worker

Instead of executing tasks directly, cron jobs dispatch to the queue:

**Benefits:**

- Automatic retry on failure
- Full monitoring and audit trail
- Non-blocking execution
- Scalable processing

### Example: Cleanup Task

**1. Create the Job:**

```php
// app/Jobs/CleanupExpiredFiles.php
class CleanupExpiredFiles implements JobInterface
{
    public function handle(array $payload): ?array
    {
        $this->outpassService->removeExpireOutpassFiles();
        
        return [
            'status' => 'completed',
            'timestamp' => (new \DateTimeImmutable())->format('Y-m-d H:i:s')
        ];
    }
}
```

**2. Create the Command:**

```php
// app/Command/CleanupExpiredFilesCommand.php
protected function execute(InputInterface $input, OutputInterface $output): int
{
    $payload = new JobPayloadBuilder();
    $job = $this->jobDispatcher->dispatch(CleanupExpiredFiles::class, $payload);
    
    $output->writeln("Job #{$job->getId()} dispatched.");
    return Command::SUCCESS;
}
```

**3. Schedule with Cron:**

```bash
0 2 * * * php passito.php app:cleanup-expired-files
```

The command dispatches the job and returns immediately. Workers process it asynchronously.

## Monitoring & Debugging

### Check Queue Status

```bash
php passito.php jobs:health
```

### View Active Workers

```bash
ps aux | grep jobs:worker
```

### Count Workers

```bash
ps aux | grep jobs:worker | grep -v grep | wc -l
```

### Check Supervisor Logs

```bash
journalctl -u passito-supervisor -f
```

### Database Queries

**Pending jobs:**

```sql
SELECT COUNT(*) FROM jobs WHERE status = 'pending';
```

**Failed jobs:**

```sql
SELECT id, type, last_error, attempts, created_at
FROM jobs
WHERE status = 'failed'
ORDER BY created_at DESC
LIMIT 10;
```

**Stale jobs:**

```sql
SELECT id, type, status, available_at, created_at
FROM jobs
WHERE status = 'pending' 
AND available_at < NOW() - INTERVAL 5 MINUTE;
```

**Recent jobs:**

```sql
SELECT id, type, status, attempts, created_at 
FROM jobs 
ORDER BY created_at DESC 
LIMIT 20;
```

## Troubleshooting

### Workers Not Processing Jobs

**Symptoms:** Jobs pile up in pending state

**Diagnosis:**

```bash
# Check supervisor status
systemctl status passito-supervisor

# Check for workers
ps aux | grep jobs:worker

# Check health
php passito.php jobs:health
```

**Solutions:**

```bash
# Restart supervisor
systemctl restart passito-supervisor

# Or start manually
php passito.php jobs:supervisor
```

### Jobs Failing Repeatedly

**Symptoms:** High failed job count

**Diagnosis:**

```sql
SELECT id, type, last_error, attempts
FROM jobs
WHERE status = 'failed'
ORDER BY created_at DESC
LIMIT 5;
```

**Common causes:**

- SMTP connection failed → Check email settings
- File not found → Check storage permissions
- Database connection lost → Check connection pool
- Memory limit exceeded → Increase PHP memory or optimize job

### High Memory Usage

**Symptoms:** System slowdown, OOM errors

**Diagnosis:**

```bash
# Check memory per worker
ps aux | grep jobs:worker | awk '{print $6}'

# Check total memory
free -h
```

**Solutions:**

1. Reduce max workers:
   ```bash
   php passito.php jobs:supervisor --max-workers=10
   ```

2. Increase PHP memory limit in `php.ini`:
   ```ini
   memory_limit = 256M
   ```

3. Optimize job handlers to use less memory

### Email Alerts Not Sending

**Diagnosis:**

```bash
# Check admin email configured
grep ADMIN_EMAIL .env

# Check SMTP settings
grep SMTP_ .env

# Test manually
php passito.php jobs:health --send-email
```

**Solutions:**

1. Configure `ADMIN_EMAIL` in `.env`
2. Verify SMTP credentials
3. Check mail logs: `tail -f /var/log/mail.log`

### Supervisor Not Scaling

**Symptoms:** Workers don't scale up/down

**Diagnosis:**

```bash
# Check configuration
systemctl cat passito-supervisor | grep ExecStart
```

**Solutions:**

Adjust thresholds:

```bash
# More aggressive scaling
--scale-up-threshold=3 --scale-down-threshold=1

# Less aggressive scaling
--scale-up-threshold=20 --scale-down-threshold=10
```

## Performance Tuning

### By System Load

| Load Type | Min Workers | Max Workers | Scale Up Threshold |
|-----------|-------------|-------------|--------------------|
| Low (< 100 jobs/day) | 1 | 3 | 5 |
| Medium (100-1K jobs/day) | 2 | 10 | 10 |
| High (1K-10K jobs/day) | 5 | 30 | 20 |
| Very High (> 10K jobs/day) | 10 | 100 | 50 |

### Resource Considerations

**Per worker:**

- Memory: 30-50 MB
- CPU: Varies by job complexity
- Database connections: 1-2

**Calculate max workers:**

```
max_workers = min(
    available_memory / 50MB,
    database_max_connections / 2,
    cpu_cores * 2
)
```

### Optimization Tips

1. **Keep jobs small and focused** - Break large tasks into smaller jobs
2. **Use job dependencies** - Chain related jobs instead of doing everything in one
3. **Optimize database queries** - Use indexes, avoid N+1 queries
4. **Clean up old jobs** - Delete completed jobs older than 30 days
5. **Monitor resource usage** - Adjust worker limits based on actual usage

## Command Reference

### Supervisor

```bash
# Start with defaults
php passito.php jobs:supervisor

# Custom configuration
php passito.php jobs:supervisor \
  --min-workers=2 \
  --max-workers=20 \
  --scale-up-threshold=10 \
  --scale-down-threshold=3 \
  --check-interval=10
```

### Worker

```bash
# Start single worker
php passito.php jobs:worker
```

### Health Check

```bash
# Basic check
php passito.php jobs:health

# With email alerts
php passito.php jobs:health --send-email

# For monitoring systems
php passito.php jobs:health --exit-code-on-failure

# Custom threshold
php passito.php jobs:health --alert-threshold=10
```

### Scheduled Tasks

```bash
# Dispatch cleanup job
php passito.php app:cleanup-expired-files

# Dispatch due scheduled report emails
php passito.php app:dispatch-scheduled-reports
```

### Systemd

```bash
# Start
sudo systemctl start passito-supervisor

# Stop
sudo systemctl stop passito-supervisor

# Restart
sudo systemctl restart passito-supervisor

# Status
sudo systemctl status passito-supervisor

# Enable auto-start
sudo systemctl enable passito-supervisor

# Disable auto-start
sudo systemctl disable passito-supervisor

# View logs
journalctl -u passito-supervisor -f
```

## Available Jobs

| Job Class | Purpose | Typical Duration |
|-----------|---------|------------------|
| `GenerateQrCode` | Generate QR codes for outpasses | < 1s |
| `GenerateOutpassPdf` | Generate PDF documents | 1-2s |
| `SendOutpassEmail` | Send outpass notification emails | 2-5s |
| `SendParentApproval` | Send parent verification requests | 2-5s |
| `CleanupExpiredFiles` | Remove old outpass files | 5-30s |

## Best Practices

✅ **Use supervisor in production** - Don't manually manage workers  
✅ **Enable health monitoring** - Set up email alerts  
✅ **Use systemd for auto-recovery** - Workers restart automatically  
✅ **Monitor system resources** - Adjust worker limits accordingly  
✅ **Review failed jobs regularly** - Fix root causes  
✅ **Keep jobs idempotent** - Safe to retry without side effects  
✅ **Use job dependencies** - Chain related jobs properly  
✅ **Clean up old jobs** - Delete completed jobs periodically  
✅ **Log important actions** - Aid debugging and auditing  
✅ **Test job handlers** - Ensure they handle errors gracefully  

## Emergency Procedures

### All Workers Crashed

```bash
# 1. Check status
systemctl status passito-supervisor

# 2. View logs
journalctl -u passito-supervisor -n 50

# 3. Restart
systemctl restart passito-supervisor

# 4. Verify
ps aux | grep jobs:worker
php passito.php jobs:health
```

### Queue Overload

```bash
# 1. Check queue size
php passito.php jobs:health

# 2. Temporarily increase workers
systemctl stop passito-supervisor
php passito.php jobs:supervisor --max-workers=50 &

# 3. Monitor progress
watch -n 5 'php passito.php jobs:health'

# 4. Return to normal once cleared
systemctl start passito-supervisor
```

### Database Connection Issues

```bash
# 1. Check database
mysql -u user -p -e "SELECT 1"

# 2. Check connection pool
mysql -u user -p -e "SHOW PROCESSLIST"

# 3. Restart supervisor
systemctl restart passito-supervisor
```

---

**Version:** 0.9.0  
**Last Updated:** February 2026
