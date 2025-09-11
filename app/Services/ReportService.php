<?php

namespace App\Services;

use DateTime;
use App\Entity\User;
use App\Enum\UserRole;
use App\Enum\CronFrequency;
use App\Entity\ReportConfig;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;

class ReportService
{
    public function __construct(
        private readonly UserService $userService,
        private readonly VerifierService $verifierService,
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function getLateArrivalsReport(User $adminUser): array
    {
        // Fetch late arrivals data
        $lateArrivals = $this->verifierService->fetchLateArrivals($adminUser);

        return [
            'total' => count($lateArrivals),
            'details' => $lateArrivals,
        ];
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

        $report->setIsEnabled(!$report->isEnabled());
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