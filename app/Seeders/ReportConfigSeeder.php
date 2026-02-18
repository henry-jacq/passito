<?php

namespace App\Seeders;

use DateTime;
use App\Enum\Gender;
use App\Enum\ReportKey;
use App\Enum\CronFrequency;
use App\Entity\ReportConfig;
use Doctrine\ORM\EntityManagerInterface;

class ReportConfigSeeder
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function run()
    {
        $configs = [
            [
                'reportKey' => ReportKey::DAILY_MOVEMENT,
                'frequency' => CronFrequency::DAILY,
                'enabled'   => true,
            ],
            [
                'reportKey' => ReportKey::LATE_ARRIVALS,
                'frequency' => CronFrequency::DAILY,
                'dayOfWeek' => 1, // Monday
                'enabled'   => true,
            ],
        ];

        foreach ([Gender::MALE, Gender::FEMALE] as $gender) {
            foreach ($configs as $cfg) {
                $report = $this->em->getRepository(ReportConfig::class)
                    ->findOneBy(['reportKey' => $cfg['reportKey'], 'gender' => $gender]);

                if (!$report) {
                    $report = new ReportConfig();
                    $report->setReportKey($cfg['reportKey']);
                    $report->setFrequency($cfg['frequency']);
                    $report->setIsEnabled($cfg['enabled']);

                    // Default to midnight if not provided
                    $report->setTime(new DateTime($cfg['time'] ?? '00:00:00'));
                    $report->setGender($gender);
                    $report->setCreatedAt(new DateTime());
                    $report->setUpdatedAt(new DateTime());

                    if (isset($cfg['dayOfWeek'])) {
                        $report->setDayOfWeek($cfg['dayOfWeek']);
                    }

                    $this->em->persist($report);
                }

            }
        }

        $this->em->flush();

        echo "Report configs seeded successfully!\n";
    }
}
