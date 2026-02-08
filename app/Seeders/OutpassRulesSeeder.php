<?php

namespace App\Seeders;

use DateTime;
use App\Entity\SystemSettings;
use Doctrine\ORM\EntityManagerInterface;

class OutpassRulesSeeder
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function run()
    {
        $settings = [
            'outpass_parent_approval' => [
                'male' => false,
                'female' => true,
            ],
            'outpass_weekday_college_hours_start' => [
                'male' => '08:00',
                'female' => '08:00',
            ],
            'outpass_weekday_college_hours_end' => [
                'male' => '15:30',
                'female' => '15:30',
            ],
            'outpass_weekday_overnight_start' => [
                'male' => '22:00',
                'female' => '20:30',
            ],
            'outpass_weekday_overnight_end' => [
                'male' => '04:00',
                'female' => '06:00',
            ],
            'outpass_weekend_start_time' => [
                'male' => '05:00',
                'female' => '06:00',
            ],
            'outpass_weekend_end_time' => [
                'male' => '22:00',
                'female' => '20:30',
            ],
            'outpass_late_arrival_grace_minutes' => [
                'male' => 30,
                'female' => 30,
            ],
        ];

        foreach ($settings as $keyName => $value) {
            $existingSetting = $this->em->getRepository(SystemSettings::class)
                ->findOneBy(['keyName' => $keyName]);

            if (!$existingSetting) {
                $newSetting = new SystemSettings();
                $newSetting->setKeyName($keyName);
                $newSetting->setValue(json_encode($value, JSON_UNESCAPED_SLASHES));
                $newSetting->setUpdatedAt(new DateTime());
                $this->em->persist($newSetting);
            }
        }

        $this->em->flush();

        echo "Outpass rules seeded successfully!\n";
    }
}
