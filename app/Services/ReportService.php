<?php

namespace App\Services;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use App\Entity\User;
use App\Core\Storage;
use App\Core\JobDispatcher;
use App\Core\JobPayloadBuilder;
use App\Enum\UserRole;
use App\Enum\ReportKey;
use App\Enum\CronFrequency;
use App\Jobs\SendScheduledReportEmail;
use App\Utils\CsvProcessor;
use App\Entity\ReportConfig;
use Doctrine\ORM\EntityManagerInterface;

class ReportService
{
    private array $reportHandlers;

    public function __construct(
        private readonly UserService $userService,
        private readonly VerifierService $verifierService,
        private readonly EntityManagerInterface $em,
        private readonly CsvProcessor $csvProcessor,
        private readonly Storage $storage,
        private readonly JobDispatcher $queue
    ) {
        $this->reportHandlers = [
            ReportKey::DAILY_MOVEMENT->value => 'generateDailyMovementReport',
            ReportKey::LATE_ARRIVALS->value  => 'generateLateArrivalsReport',
        ];
    }

    /**
     * Generate report and return the stored file path
     */
    public function generateReport(User $user, ReportConfig $config): ?string
    {
        $reportKey = $config->getReportKey();
        $key = $reportKey instanceof ReportKey ? $reportKey->value : $reportKey;

        if (!isset($this->reportHandlers[$key])) {
            throw new \RuntimeException("Unknown report key: $key");
        }

        $method = $this->reportHandlers[$key];
        return $this->$method($user, $config);
    }

    /**
     * Generate Daily Movement Report
     */
    private function generateDailyMovementReport(User $user, ReportConfig $config): ?string
    {
        // Fetch logs
        $today = new DateTime('today');
        $checkedIn = $this->verifierService->fetchCheckedInLogs($user, $today);
        $checkedOut = $this->verifierService->fetchCheckedOutLogs($user, $today);

        // Define headers for CSV
        $headers = ['Name', 'Roll No', 'Email', 'Date', 'Time', 'Action'];

        $rows = [];

        // Map checked-in logs
        foreach ($checkedIn as $log) {
            $rows[] = [
                '_sort' => $log->getInTime()?->getTimestamp() ?? 0,
                $log->getOutpass()->getStudent()->getUser()->getName() ?? null,
                $log->getOutpass()->getStudent()->getRollNo() ?? null,
                $log->getOutpass()->getStudent()->getUser()->getEmail() ?? null,
                $log->getInTime()->format('d-m-Y') ?? null,
                $log->getInTime()->format('h:i:s A') ?? null,
                'CHECKED_IN',
            ];
        }

        // Map checked-out logs
        foreach ($checkedOut as $log) {
            $rows[] = [
                '_sort' => $log->getOutTime()?->getTimestamp() ?? 0,
                $log->getOutpass()->getStudent()->getUser()->getName() ?? null,
                $log->getOutpass()->getStudent()->getRollNo() ?? null,
                $log->getOutpass()->getStudent()->getUser()->getEmail() ?? null,
                $log->getOutTime()->format('d-m-Y') ?? null,
                $log->getOutTime()->format('h:i:s A') ?? null,
                'CHECKED_OUT',
            ];
        }

        if (empty($rows)) {
            return null;
        }

        if (!empty($rows)) {
            usort($rows, fn ($a, $b) => $a['_sort'] <=> $b['_sort']);
            $rows = array_map(function ($row) {
                unset($row['_sort']);
                return $row;
            }, $rows);
        }

        // Save into storage
        // The prefix will have date in future
        return $this->saveCsv($rows, $headers, 'reports/daily_movement', 'daily_movement');
    }


    /**
     * Generate Late Arrivals Report
     */
    private function generateLateArrivalsReport(User $user, ReportConfig $config): ?string
    {
        $today = new DateTime('today');
        $lateArrivals = $this->verifierService->fetchLateArrivals($user, $today);

        $headers = [
            'Student Name',
            'Roll No',
            'Email',
            'From Duration',
            'To Duration',
            'Check-In',
            'Check-Out',
            'Late Duration'
        ];
        if (empty($lateArrivals)) {
            return null;
        }

        $rows = $this->csvProcessor->mapDataToRows($lateArrivals, function ($log) {
            $fromTime = $log->getOutpass()->getFromTime()->format('h:i:s A') ?? null;
            $fromDate = $log->getOutpass()->getFromDate()->format('d-m-Y') ?? null;
            $toTime = $log->getOutpass()->getToTime()->format('h:i:s A') ?? null;
            $toDate = $log->getOutpass()->getToDate()->format('d-m-Y') ?? null;
            
            return [
                $log->getOutpass()->getStudent()->getUser()->getName() ?? null,
                $log->getOutpass()->getStudent()->getRollNo() ?? null,
                $log->getOutpass()->getStudent()->getUser()->getEmail() ?? null,
                $fromDate . ' ' . $fromTime,
                $toDate . ' ' . $toTime,
                $log->getOutTime()->format('d-m-Y h:i:s A') ?? null,
                $log->getInTime()->format('d-m-Y h:i:s A') ?? null,
                $log->getLateDuration()
            ];
        });

        return $this->saveCsv($rows, $headers, 'reports/late_arrivals', 'late_arrivals');
    }

    /**
     * Save CSV into storage and return its path
     */
    private function saveCsv(array $rows, array $headers, string $directory, ?string $prefix=null): string
    {
        $relativePath = $this->storage->generateFileName($directory, "csv", $prefix);
        if ($this->storage->isLocal()) {
            $fullPath = $this->storage->getFullPath($relativePath, true);
            $this->csvProcessor->writeToFile($fullPath, $headers, $rows);
            return $relativePath;
        }

        $tmp = tempnam(sys_get_temp_dir(), 'report_csv_');
        if ($tmp === false) {
            throw new \RuntimeException('Unable to create temporary report file');
        }

        try {
            $this->csvProcessor->writeToFile($tmp, $headers, $rows);
            $stream = fopen($tmp, 'rb');
            if ($stream === false) {
                throw new \RuntimeException('Unable to open temporary report file stream');
            }

            try {
                $this->storage->writeStream($relativePath, $stream);
            } finally {
                fclose($stream);
            }
        } finally {
            @unlink($tmp);
        }

        return $relativePath;
    }

    /**
     * Get all report settings
     *
     * @param User $adminUser
     * @return array
     */
    public function getAllReportSettings(User $adminUser): array
    {
        $settings = $this->em->getRepository(ReportConfig::class)->findBy([
            'gender' => $adminUser->getGender()
        ]);

        return $settings;
    }

    /**
     * Get report setting by ID
     *
     * @param int $reportId
     * @return ReportConfig|null
     */
    public function getReportSettingById(int $reportId): ReportConfig|null
    {
        return $this->em->getRepository(ReportConfig::class)->find($reportId);
    }

    /**
     * Get Report Setting by ReportKey and User Gender
     */
    public function getReportSettingByKey(ReportKey $key, User $user): ?ReportConfig
    {
        return $this->em->getRepository(ReportConfig::class)->findOneBy([
            'reportKey' => $key,
            'gender' => $user->getGender()
        ]);
    }

    /**
     * Toggle report status (enable/disable) by its Id
     * 
     * @param int $reportId
     * @return bool|ReportConfig
     */
    public function toggleReportStatus(int $reportId): bool|ReportConfig
    {
        $report = $this->getReportSettingById($reportId);

        if (!$report) {
            return false;
        }

        $report->setIsEnabled(!$report->getIsEnabled());
        $report->setNextSend(
            $report->getIsEnabled()
                ? $this->computeNextSend($report, new DateTimeImmutable())
                : null
        );
        $report->setUpdatedAt(new DateTime());

        $this->em->persist($report);
        $this->em->flush();

        return $report;
    }

    /**
     * Update report settings by its Id
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateReportSettingsById(int $id, array $data): bool
    {
        $report = $this->getReportSettingById($id);

        if (!$report) {
            return false;
        }

        $frequency = CronFrequency::tryFrom((string) ($data['frequency'] ?? ''));
        if (!$frequency instanceof CronFrequency) {
            return false;
        }

        $dateTime = DateTime::createFromFormat('H:i', (string) ($data['time'] ?? ''));

        if ($dateTime == false) {
            return false;
        }

        $report->setDayOfWeek(isset($data['dayOfWeek']) && $data['dayOfWeek'] !== '' ? (int) $data['dayOfWeek'] : null);
        $report->setDayOfMonth(isset($data['dayOfMonth']) && $data['dayOfMonth'] !== '' ? (int) $data['dayOfMonth'] : null);
        $report->setMonth(isset($data['month']) && $data['month'] !== '' ? (int) $data['month'] : null);
        $report->setFrequency($frequency);
        $report->setTime($dateTime);
        try {
            $this->applyFrequencyOptions($report);
        } catch (\InvalidArgumentException) {
            return false;
        }

        // Reset recipients; wardens are explicit recipients, super admins are implicit.
        foreach ($report->getRecipients() as $user) {
            $report->removeRecipient($user);
        }

        $recipientIds = is_array($data['recipients'] ?? null) ? $data['recipients'] : [];

        // Add new recipients if got
        if (count($recipientIds) > 0) {
            foreach ($recipientIds as $recipient) {
                $user = $this->userService->getUserById((int) $recipient);
                if (!$user instanceof User || !UserRole::isAdmin($user->getRole()->value)) {
                    return false;
                }
                $report->addRecipient($user);
            }
        }

        $report->setNextSend(
            $report->getIsEnabled()
                ? $this->computeNextSend($report, new DateTimeImmutable())
                : null
        );
        $report->setUpdatedAt(new DateTime());

        $this->em->persist($report);
        $this->em->flush();

        return true;
    }

    /**
     * Dispatch due scheduled reports into the jobs queue.
     * Returns summary counters for observability/CLI output.
     */
    public function dispatchDueScheduledReports(DateTimeImmutable $asOf, bool $dryRun = false): array
    {
        $configs = $this->em->getRepository(ReportConfig::class)->findBy(['isEnabled' => true]);
        $summary = [
            'checked' => count($configs),
            'initialized' => 0,
            'due' => 0,
            'dispatched' => 0,
            'skipped_no_recipients' => 0,
        ];

        foreach ($configs as $config) {
            $nextSend = $config->getNextSend();
            if (!$nextSend instanceof DateTimeInterface) {
                $summary['initialized']++;
                if (!$dryRun) {
                    $config->setNextSend($this->computeNextSend($config, $asOf));
                    $config->setUpdatedAt(new DateTime());
                    $this->em->persist($config);
                }
                continue;
            }

            $dueAt = $this->toImmutable($nextSend);
            if ($dueAt > $asOf) {
                continue;
            }

            $summary['due']++;

            $recipientIds = array_values(array_unique(array_merge(
                $this->extractRecipientIds($config),
                $this->getMandatorySuperAdminRecipientIds($config)
            )));
            if ($recipientIds === []) {
                $summary['skipped_no_recipients']++;
                if (!$dryRun) {
                    $config->setNextSend($this->advanceNextSendBeyond($config, $dueAt, $asOf));
                    $config->setUpdatedAt(new DateTime());
                    $this->em->persist($config);
                }
                continue;
            }

            if (!$dryRun) {
                $config->setNextSend($this->advanceNextSendBeyond($config, $dueAt, $asOf));
                $config->setUpdatedAt(new DateTime());
                $this->em->persist($config);

                $payload = JobPayloadBuilder::create()
                    ->set('report_config_id', $config->getId())
                    ->set('scheduled_for', $dueAt->format('Y-m-d H:i:s'))
                    ->set('recipient_ids', $recipientIds)
                    ->set('queued_at', $asOf->format('Y-m-d H:i:s'));

                $this->queue->dispatch(SendScheduledReportEmail::class, $payload);
                $summary['dispatched']++;
            }
        }

        if (!$dryRun) {
            $this->em->flush();
        }

        return $summary;
    }

    public function computeNextSend(ReportConfig $config, DateTimeInterface $from): DateTimeImmutable
    {
        $anchor = $this->toImmutable($from);
        $time = $config->getTime();
        $hour = (int) $time->format('H');
        $minute = (int) $time->format('i');
        $second = (int) $time->format('s');

        $frequency = $config->getFrequency();

        return match ($frequency) {
            CronFrequency::DAILY => $this->nextDaily($anchor, $hour, $minute, $second),
            CronFrequency::WEEKLY => $this->nextWeekly(
                $anchor,
                $hour,
                $minute,
                $second,
                $config->getDayOfWeek() ?? 1
            ),
            CronFrequency::MONTHLY => $this->nextMonthly(
                $anchor,
                $hour,
                $minute,
                $second,
                $config->getDayOfMonth() ?? 1
            ),
            CronFrequency::YEARLY => $this->nextYearly(
                $anchor,
                $hour,
                $minute,
                $second,
                $config->getMonth() ?? 1,
                $config->getDayOfMonth() ?? 1
            ),
        };
    }

    private function applyFrequencyOptions(ReportConfig $report): void
    {
        $frequency = $report->getFrequency();
        $dayOfWeek = $report->getDayOfWeek();
        $dayOfMonth = $report->getDayOfMonth();
        $month = $report->getMonth();

        if ($frequency === CronFrequency::DAILY) {
            $report->setDayOfWeek(null);
            $report->setDayOfMonth(null);
            $report->setMonth(null);
            return;
        }

        if ($frequency === CronFrequency::WEEKLY) {
            if ($dayOfWeek === null || $dayOfWeek < 1 || $dayOfWeek > 7) {
                throw new \InvalidArgumentException('Invalid dayOfWeek for weekly report');
            }
            $report->setDayOfMonth(null);
            $report->setMonth(null);
            return;
        }

        if ($frequency === CronFrequency::MONTHLY) {
            if ($dayOfMonth === null || $dayOfMonth < 1 || $dayOfMonth > 31) {
                throw new \InvalidArgumentException('Invalid dayOfMonth for monthly report');
            }
            $report->setDayOfWeek(null);
            $report->setMonth(null);
            return;
        }

        if ($month === null || $month < 1 || $month > 12) {
            throw new \InvalidArgumentException('Invalid month for yearly report');
        }
        if ($dayOfMonth === null || $dayOfMonth < 1 || $dayOfMonth > 31) {
            throw new \InvalidArgumentException('Invalid dayOfMonth for yearly report');
        }

        $report->setDayOfWeek(null);
    }

    private function extractRecipientIds(ReportConfig $config): array
    {
        $ids = [];
        foreach ($config->getRecipients() as $recipient) {
            if (!$recipient instanceof User) {
                continue;
            }

            $role = $recipient->getRole()->value;
            if (!UserRole::isAdmin($role)) {
                continue;
            }

            if (!filter_var($recipient->getEmail(), FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $ids[] = (int) $recipient->getId();
        }

        return array_values(array_unique($ids));
    }

    /**
     * Super admin recipients are always included implicitly
     * and are not required to be stored in report_config_recipients.
     */
    private function getMandatorySuperAdminRecipientIds(ReportConfig $config): array
    {
        $superAdmins = $this->em->getRepository(User::class)->findBy([
            'role' => UserRole::SUPER_ADMIN,
            'gender' => $config->getGender(),
        ]);

        $ids = [];
        foreach ($superAdmins as $superAdmin) {
            if (!filter_var($superAdmin->getEmail(), FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            $ids[] = (int) $superAdmin->getId();
        }

        return array_values(array_unique($ids));
    }

    private function nextDaily(DateTimeImmutable $from, int $hour, int $minute, int $second): DateTimeImmutable
    {
        $candidate = $from->setTime($hour, $minute, $second);
        if ($candidate <= $from) {
            $candidate = $candidate->modify('+1 day');
        }
        return $candidate;
    }

    private function nextWeekly(
        DateTimeImmutable $from,
        int $hour,
        int $minute,
        int $second,
        int $dayOfWeek
    ): DateTimeImmutable {
        $targetDow = max(1, min(7, $dayOfWeek));
        $currentDow = (int) $from->format('N');
        $offset = $targetDow - $currentDow;

        $candidate = $from
            ->modify(sprintf('%+d days', $offset))
            ->setTime($hour, $minute, $second);

        if ($candidate <= $from) {
            $candidate = $candidate->modify('+7 days');
        }

        return $candidate;
    }

    private function nextMonthly(
        DateTimeImmutable $from,
        int $hour,
        int $minute,
        int $second,
        int $dayOfMonth
    ): DateTimeImmutable {
        $day = max(1, min(31, $dayOfMonth));
        $year = (int) $from->format('Y');
        $month = (int) $from->format('n');

        $candidate = $this->buildScheduledDate($from, $year, $month, $day, $hour, $minute, $second);
        if ($candidate <= $from) {
            $nextMonth = $from->modify('first day of next month');
            $year = (int) $nextMonth->format('Y');
            $month = (int) $nextMonth->format('n');
            $candidate = $this->buildScheduledDate($from, $year, $month, $day, $hour, $minute, $second);
        }

        return $candidate;
    }

    private function nextYearly(
        DateTimeImmutable $from,
        int $hour,
        int $minute,
        int $second,
        int $month,
        int $dayOfMonth
    ): DateTimeImmutable {
        $safeMonth = max(1, min(12, $month));
        $safeDay = max(1, min(31, $dayOfMonth));
        $year = (int) $from->format('Y');

        $candidate = $this->buildScheduledDate($from, $year, $safeMonth, $safeDay, $hour, $minute, $second);
        if ($candidate <= $from) {
            $candidate = $this->buildScheduledDate($from, $year + 1, $safeMonth, $safeDay, $hour, $minute, $second);
        }

        return $candidate;
    }

    private function buildScheduledDate(
        DateTimeImmutable $anchor,
        int $year,
        int $month,
        int $requestedDay,
        int $hour,
        int $minute,
        int $second
    ): DateTimeImmutable {
        $lastDay = (int) cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $day = min($requestedDay, $lastDay);
        $timezone = $anchor->getTimezone() ?: new DateTimeZone(date_default_timezone_get());

        return (new DateTimeImmutable('now', $timezone))
            ->setDate($year, $month, $day)
            ->setTime($hour, $minute, $second);
    }

    private function toImmutable(DateTimeInterface $dateTime): DateTimeImmutable
    {
        if ($dateTime instanceof DateTimeImmutable) {
            return $dateTime;
        }

        return DateTimeImmutable::createFromMutable($dateTime);
    }

    private function advanceNextSendBeyond(
        ReportConfig $config,
        DateTimeImmutable $from,
        DateTimeImmutable $asOf
    ): DateTimeImmutable {
        $next = $this->computeNextSend($config, $from);
        $guard = 0;
        while ($next <= $asOf && $guard < 5000) {
            $next = $this->computeNextSend($config, $next);
            $guard++;
        }

        return $next;
    }
}
