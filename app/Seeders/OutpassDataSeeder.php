<?php

namespace App\Seeders;

use DateTime;
use App\Entity\OutpassRequest;
use App\Entity\OutpassTemplate;
use App\Entity\Student;
use App\Enum\OutpassStatus;
use Doctrine\ORM\EntityManagerInterface;

class OutpassDataSeeder
{
    public function __construct(
        private readonly EntityManagerInterface $em,
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

        for ($i = 1; $i <= 20; $i++) {
            $fromDate = new DateTime();
            $toDate = (clone $fromDate)->modify('+' . rand(1, 3) . ' days');
            $fromTime = new DateTime(sprintf('%02d:%02d:00', rand(6, 18), rand(0, 59)));
            $toTime = new DateTime(sprintf('%02d:%02d:00', rand(19, 23), rand(0, 59)));

            $student = $this->em->getRepository(Student::class)->find($this->studentId);

            $outpass = new OutpassRequest();
            $outpass->setStudent($student);
            $outpass->setFromDate($fromDate);
            $outpass->setToDate($toDate);
            $outpass->setFromTime($fromTime);
            $outpass->setToTime($toTime);
            $outpass->setTemplate($passType);
            $outpass->setDestination($destinations[array_rand($destinations)]);
            $outpass->setReason('Personal visit');
            $outpass->setStatus(OutpassStatus::PENDING);
            $outpass->setCreatedAt(new DateTime());

            $this->em->persist($outpass);
        }

        $this->em->flush();

        echo "Outpass data seeded successfully!\n";
    }
}