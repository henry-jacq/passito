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
            ['keyName' => 'setup_complete', 'value' => 'true'],
            ['keyName' => 'maintenance_mode', 'value' => 'false'],
            ['keyName' => 'admin_created', 'value' => 'false'],
            ['keyName' => 'app_name', 'value' => 'Passito'],
            ['keyName' => 'email_settings', 'value' => '{}'],
            ['keyName' => 'max_outpasses_per_day', 'value' => '2'],
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
