<?php

namespace App\Seeders;

use DateTime;
use App\Entity\Institution;
use App\Enum\InstitutionType;
use Doctrine\ORM\EntityManagerInterface;

class InstitutionSeeder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function run()
    {
        $institutions = [
            ['name' => 'SSN College of Engineering', 'address' => 'Kalavakkam, Chennai', 'type' => InstitutionType::COLLEGE],
            ['name' => 'Shiv Nadar University', 'address' => 'Kalavakkam, Chennai', 'type' => InstitutionType::UNIVERSITY],
        ];

        foreach ($institutions as $institution) {
            $existingIns = $this->em->getRepository(Institution::class)
                ->findOneBy(['name' => $institution['name']]);

            if (!$existingIns) {
                $newIns = new Institution();
                $newIns->setName($institution['name']);
                $newIns->setAddress($institution['address']);
                $newIns->setType($institution['type']);
                $newIns->setCreatedAt(new DateTime());
                $this->em->persist($newIns);
            }
        }

        $this->em->flush();

        echo "Institutions seeded successfully!\n";
    }
}
