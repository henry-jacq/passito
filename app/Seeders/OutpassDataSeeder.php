<?php

namespace App\Seeders;

use DateTime;
use App\Entity\OutpassRequest;
use App\Entity\Student;
use App\Enum\OutpassStatus;
use App\Enum\OutpassType;
use Doctrine\ORM\EntityManagerInterface;

class OutpassDataSeeder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function run()
    {
        $destinations = [
            'Chennai', 'Mahabalipuram', 'Pondicherry', 'Vellore', 'Kanchipuram',
            'Tiruvannamalai', 'Madurai', 'Coimbatore', 'Trichy', 'Salem'
        ];

        $passTypes = ['outing', 'home'];

        for ($i = 1; $i <= 20; $i++) {
            $fromDate = new DateTime();
            $toDate = (clone $fromDate)->modify('+' . rand(1, 3) . ' days');
            $fromTime = new DateTime(sprintf('%02d:%02d:00', rand(6, 18), rand(0, 59)));
            $toTime = new DateTime(sprintf('%02d:%02d:00', rand(19, 23), rand(0, 59)));

            $student = $this->em->getRepository(Student::class)->find(1);

            $outpass = new OutpassRequest();
            $outpass->setStudent($student);
            $outpass->setFromDate($fromDate);
            $outpass->setToDate($toDate);
            $outpass->setFromTime($fromTime);
            $outpass->setToTime($toTime);
            $passTypeSelected = $passTypes[array_rand($passTypes)];
            $outpass->setPassType(OutpassType::from($passTypeSelected));
            $outpass->setDestination($destinations[array_rand($destinations)]);

            if ($passTypeSelected != OutpassType::HOME->value) {
                $outpass->setPurpose('Personal visit');
            } else {
                $outpass->setPurpose(ucfirst(OutpassType::HOME->value));
            }
            $outpass->setStatus(OutpassStatus::PENDING);
            $outpass->setCreatedAt(new DateTime());

            $this->em->persist($outpass);
        }

        $this->em->flush();

        echo "Outpass data seeded successfully!\n";
    }
}