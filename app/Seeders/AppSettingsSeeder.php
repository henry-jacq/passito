<?php

namespace App\Seeders;

use DateTime;
use App\Entity\SystemSettings;
use Doctrine\ORM\EntityManagerInterface;

class AppSettingsSeeder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function run()
    {
        $settings = [
            // App Settings
            ['keyName' => 'app_name', 'value' => 'Passito'],
            ['keyName' => 'setup_complete', 'value' => false],
            ['keyName' => 'admin_created', 'value' => false],
            ['keyName' => 'maintenance_mode', 'value' => false],
            ['keyName' => 'max_outpasses_per_day', 'value' => 2],
            ['keyName' => 'lock_requests', 'value' => ['male' => false, 'female' => false]],
            ['keyName' => 'verifier_mode', 'value' => 'manual'],
            
            // Outpass Rules Settings
            ['keyName' => 'outpass_parent_approval', 'value' => ['male' => false, 'female' => true]],
            ['keyName' => 'outpass_weekday_college_hours_start', 'value' => ['male' => '08:00', 'female' => '08:00']],
            ['keyName' => 'outpass_weekday_college_hours_end', 'value' => ['male' => '15:30', 'female' => '15:30']],
            ['keyName' => 'outpass_weekday_overnight_start', 'value' => ['male' => '22:00', 'female' => '20:30']],
            ['keyName' => 'outpass_weekday_overnight_end', 'value' => ['male' => '04:00', 'female' => '06:00']],
            ['keyName' => 'outpass_weekend_start_time', 'value' => ['male' => '05:00', 'female' => '06:00']],
            ['keyName' => 'outpass_weekend_end_time', 'value' => ['male' => '22:00', 'female' => '20:30']],
            ['keyName' => 'outpass_late_arrival_grace_minutes', 'value' => ['male' => 30, 'female' => 30]],
        ];

        foreach ($settings as $setting) {
            $existingSetting = $this->em->getRepository(SystemSettings::class)
                ->findOneBy(['keyName' => $setting['keyName']]);

            if (!$existingSetting) {
                $newSetting = new SystemSettings();
                $newSetting->setKeyName($setting['keyName']);
                $value = $setting['value'];
                $newSetting->setValue(is_string($value) ? $value : json_encode($value, JSON_UNESCAPED_SLASHES));
                $newSetting->setUpdatedAt(new DateTime());

                $this->em->persist($newSetting);
            }
        }

        $this->em->flush();

        echo "App Settings seeded successfully!\n";
    }
}
