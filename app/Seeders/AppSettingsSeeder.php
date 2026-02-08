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
            ['keyName' => 'app_name', 'value' => 'Passito'],
            ['keyName' => 'setup_complete', 'value' => false],
            ['keyName' => 'admin_created', 'value' => false],
            ['keyName' => 'maintenance_mode', 'value' => false],
            ['keyName' => 'max_outpasses_per_day', 'value' => 2],
            ['keyName' => 'lock_requests', 'value' => ['male' => false, 'female' => false]],
            ['keyName' => 'verifier_mode', 'value' => 'manual'],
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
