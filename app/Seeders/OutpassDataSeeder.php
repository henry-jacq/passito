<?php

namespace App\Seeders;

use DateTime;
use App\Entity\OutpassTemplate;
use App\Entity\Student;
use App\Dto\CreateOutpassDto;
use App\Services\OutpassService;
use Doctrine\ORM\EntityManagerInterface;

class OutpassDataSeeder
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly OutpassService $outpassService,
        private readonly int $studentId
    )
    {
    }

    public function run()
    {
        $destinations = [
            'Chennai', 'Mahabalipuram', 'Pondicherry', 'Vellore', 'Kanchipuram',
            'Tiruvannamalai', 'Madurai', 'Coimbatore', 'Trichy', 'Salem'
        ];

        $passType = $this->em->getRepository(OutpassTemplate::class)->findOneBy([
            'name' => 'Home Pass'
        ]);

        $student = $this->em->getRepository(Student::class)->find($this->studentId);

        if (!$student || !$passType) {
            echo "Student or template not found!\n";
            return;
        }

        for ($i = 1; $i <= 20; $i++) {
            $fromDate = new DateTime();
            $toDate = (clone $fromDate)->modify('+' . rand(1, 3) . ' days');
            $fromTime = new DateTime(sprintf('%02d:%02d:00', rand(6, 18), rand(0, 59)));
            $toTime = new DateTime(sprintf('%02d:%02d:00', rand(19, 23), rand(0, 59)));

            try {
                $outpassDto = CreateOutpassDto::create(
                    student: $student,
                    template: $passType,
                    fromDate: $fromDate,
                    toDate: $toDate,
                    fromTime: $fromTime,
                    toTime: $toTime,
                    destination: $destinations[array_rand($destinations)],
                    reason: 'Personal visit',
                    attachments: [],
                    customValues: null
                );

                $this->outpassService->createOutpass($outpassDto);
            } catch (\InvalidArgumentException $e) {
                echo "Failed to create outpass: " . $e->getMessage() . "\n";
                continue;
            }
        }

        echo "Outpass data seeded successfully!\n";
    }
}
