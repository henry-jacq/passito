<?php

namespace App\Services;

use App\Enum\Gender;
use App\Entity\OutpassSettings;
use Doctrine\ORM\EntityManagerInterface;

class SettingsService
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function getOutpassSettings(Gender $gender)
    {
        $settings = $this->em->getRepository(OutpassSettings::class)
            ->findBy(['type' => $gender]);

        // Return the first element if there's only one, otherwise return the array
        return count($settings) === 1 ? $settings[0] : $settings;
    }
}