<?php

namespace App\Seeders;

use App\Entity\AcademicYear;
use Doctrine\ORM\EntityManagerInterface;

class AcademicYearSeeder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function run()
    {
        $academicYears = [
            ['label' => 'UG2022-26', 'start_year' => 2022, 'end_year' => 2026, 'status' => true],
            ['label' => 'UG2024-27', 'start_year' => 2024, 'end_year' => 2027, 'status' => true],
            ['label' => 'UG2025-28', 'start_year' => 2025, 'end_year' => 2028, 'status' => false],
        ];

        foreach ($academicYears as $year) {
            $existing = $this->em->getRepository(AcademicYear::class)
                ->findOneBy(['label' => $year['label']]);

            if (!$existing) {
                $entity = new AcademicYear();
                $entity->setLabel($year['label']);
                $entity->setStartYear($year['start_year']);
                $entity->setEndYear($year['end_year']);
                $entity->setStatus($year['status']);
                $this->em->persist($entity);
            }
        }

        $this->em->flush();

        echo "Academic years seeded successfully!\n";
    }
}
