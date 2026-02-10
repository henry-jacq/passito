<?php

namespace App\Services;

use DateTime;
use App\Entity\User;
use App\Core\Storage;
use App\Enum\UserRole;
use App\Enum\ReportKey;
use App\Enum\CronFrequency;
use App\Utils\CsvProcessor;
use App\Entity\ReportConfig;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;

class ReportService
{
    private array $reportHandlers;

    public function __construct(
        private readonly UserService $userService,
        private readonly VerifierService $verifierService,
        private readonly EntityManagerInterface $em,
        private readonly CsvProcessor $csvProcessor,
        private readonly Storage $storage
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
        $headers = ['Name', 'Digital ID', 'Email', 'Date', 'Time', 'Action'];

        $rows = [];

        // Map checked-in logs
        foreach ($checkedIn as $log) {
            $rows[] = [
                '_sort' => $log->getInTime()?->getTimestamp() ?? 0,
                $log->getOutpass()->getStudent()->getUser()->getName() ?? null,
                $log->getOutpass()->getStudent()->getDigitalId() ?? null,
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
                $log->getOutpass()->getStudent()->getDigitalId() ?? null,
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
            'Digital ID',
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
                $log->getOutpass()->getStudent()->getDigitalId() ?? null,
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
        $fullPath = $this->storage->getFullPath($relativePath, true);

        $this->csvProcessor->writeToFile($fullPath, $headers, $rows);

        return $relativePath; // stored path for queue + retrieval
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

        $report->setFrequency(CronFrequency::from($data['frequency']));
        $report->setDayOfWeek($data['dayOfWeek']);
        $report->setDayOfMonth($data['dayOfMonth']);
        $report->setMonth($data['month']);
        $dateTime = DateTime::createFromFormat('H:i', $data['time']);

        if ($dateTime == false) {
            return false;
        }

        $report->setTime($dateTime);

        // Remove the recipients who are not super admin
        foreach ($report->getRecipients() as $user) {
            if (!UserRole::isSuperAdmin($user->getRole()->value)) {
                $report->removeRecipient($user);
            }
        }

        // Add new recipients if got
        if (count($data['recipients']) > 0) {
            foreach ($data['recipients'] as $recipient) {
                $user = $this->userService->getUserById((int) $recipient);
                if (!$user instanceof User && !UserRole::isAdmin($user->getRole()->value)) {
                    return false;
                }
                $report->addRecipient($user);
            }
        }

        $this->em->persist($report);
        $this->em->flush();

        return true;
    }
}
