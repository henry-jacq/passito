<?php

namespace App\Seeders;

use DateTime;
use App\Entity\OutpassSettings;
use App\Enum\Gender;
use Doctrine\ORM\EntityManagerInterface;

class OutpassRulesSeeder
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function run()
    {
        $settings = [
            [
                'type' => 'male',
                'dailyLimit' => null,
                'weeklyLimit' => null,
                'parentApproval' => false,
                'companionVerification' => false,
                'emergencyContactNotification' => false,
                'weekdayCollegeHoursStart' => '08:00:00',
                'weekdayCollegeHoursEnd' => '15:30:00',
                'weekdayOvernightStart' => '22:00:00',
                'weekdayOvernightEnd' => '04:00:00',
                'weekendStartTime' => '05:00:00',
                'weekendEndTime' => '22:00:00',
                'emailNotification' => true,
                'smsNotification' => false,
                'appNotification' => false,
            ],
            [
                'type' => 'female',
                'dailyLimit' => null,
                'weeklyLimit' => null,
                'parentApproval' => true,
                'companionVerification' => false,
                'emergencyContactNotification' => false, // Different for female
                'weekdayCollegeHoursStart' => '08:00:00',
                'weekdayCollegeHoursEnd' => '15:30:00',
                'weekdayOvernightStart' => '20:30:00', // Different for female
                'weekdayOvernightEnd' => '06:00:00',
                'weekendStartTime' => '06:00:00',
                'weekendEndTime' => '20:30:00', // Different for female
                'emailNotification' => true,
                'smsNotification' => true, // Different for female
                'appNotification' => false,
            ],
        ];

        foreach ($settings as $setting) {
            $existingSetting = $this->em->getRepository(OutpassSettings::class)
                ->findOneBy(['type' => $setting['type']]);

            if (!$existingSetting) {
                $newSetting = new OutpassSettings();
                $newSetting->setType(Gender::from($setting['type']));
                $newSetting->setDailyLimit($setting['dailyLimit']);
                $newSetting->setWeeklyLimit($setting['weeklyLimit']);
                $newSetting->setParentApproval($setting['parentApproval']);
                $newSetting->setCompanionVerification($setting['companionVerification']);
                $newSetting->setEmergencyContactNotification($setting['emergencyContactNotification']);
                $newSetting->setWeekdayCollegeHoursStart(new \DateTime($setting['weekdayCollegeHoursStart']));
                $newSetting->setWeekdayCollegeHoursEnd(new \DateTime($setting['weekdayCollegeHoursEnd']));
                $newSetting->setWeekdayOvernightStart(new \DateTime($setting['weekdayOvernightStart']));
                $newSetting->setWeekdayOvernightEnd(new \DateTime($setting['weekdayOvernightEnd']));
                $newSetting->setWeekendStartTime(new \DateTime($setting['weekendStartTime']));
                $newSetting->setWeekendEndTime(new \DateTime($setting['weekendEndTime']));
                $newSetting->setEmailNotification($setting['emailNotification']);
                $newSetting->setSmsNotification($setting['smsNotification']);
                $newSetting->setAppNotification($setting['appNotification']);

                $this->em->persist($newSetting);
            }
        }

        $this->em->flush();

        echo "Outpass rules seeded successfully!\n";
    }
}
