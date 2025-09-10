<?php

namespace App\Seeders;

use DateTime;
use App\Enum\Gender;
use App\Enum\ReportKey;
use App\Enum\CronFrequency;
use App\Entity\User;
use App\Entity\ReportConfig;
use App\Enum\UserRole;
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
                'time'      => '08:00:00',
                'enabled'   => true,
            ],
            [
                'reportKey' => ReportKey::LATE_ARRIVALS,
                'frequency' => CronFrequency::DAILY,
                'dayOfWeek' => 1, // Monday
                'time'      => '09:00:00',
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
                    $report->setTime(new DateTime($cfg['time']));
                    $report->setGender($gender);
                    $report->setCreatedAt(new DateTime());
                    $report->setUpdatedAt(new DateTime());

                    if (isset($cfg['dayOfWeek'])) {
                        $report->setDayOfWeek($cfg['dayOfWeek']);
                    }

                    $this->em->persist($report);
                }

                // attach recipients (super admins of same gender)
                $superAdmins = $this->em->getRepository(User::class)->findBy([
                    'role'   => UserRole::SUPER_ADMIN,
                    'gender' => $gender,
                ]);

                foreach ($superAdmins as $admin) {
                    if (!$report->getRecipients()->contains($admin)) {
                        $report->getRecipients()->add($admin);
                    }
                }
            }
        }

        $this->em->flush();

        echo "Report configs and default recipients seeded successfully!\n";
    }
}
