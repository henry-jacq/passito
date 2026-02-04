<?php

namespace App\Seeders;

use DateTime;
use App\Entity\Settings;
use Doctrine\ORM\EntityManagerInterface;

class AppSettingsSeeder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function run()
    {
        $settings = [
            ['keyName' => 'app_name', 'value' => 'Passito'],
            ['keyName' => 'setup_complete', 'value' => 'false'],
            ['keyName' => 'admin_created', 'value' => 'false'],
            ['keyName' => 'maintenance_mode', 'value' => 'false'],
            ['keyName' => 'max_outpasses_per_day', 'value' => '2'],
            ['keyName' => 'lock_requests_male', 'value' => 'false'],
            ['keyName' => 'lock_requests_female', 'value' => 'false'],
        ];

        foreach ($settings as $setting) {
            $existingSetting = $this->em->getRepository(Settings::class)
                ->findOneBy(['keyName' => $setting['keyName']]);

            if (!$existingSetting) {
                $newSetting = new Settings();
                $newSetting->setKeyName($setting['keyName']);
                $newSetting->setValue($setting['value']);
                $newSetting->setUpdatedAt(new DateTime());

                $this->em->persist($newSetting);
            }
        }

        $this->em->flush();

        echo "App Settings seeded successfully!\n";
    }
}
