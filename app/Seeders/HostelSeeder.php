<?php

namespace App\Seeders;

use App\Entity\Hostel;
use App\Enum\HostelType;
use Doctrine\ORM\EntityManagerInterface;

class HostelSeeder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function run()
    {
        $hostels = [
            ['name' => 'Hostel-1', 'category' => 'Non-AC Shared (Common Bath)', 'type' => HostelType::GENTS],
            ['name' => 'Hostel-2', 'category' => 'AC with Attached Bath and Balcony', 'type' => HostelType::GENTS],
            ['name' => 'Hostel-A', 'category' => 'AC Shared Room', 'type' => HostelType::LADIES],
        ];

        foreach ($hostels as $hostelData) {
            $existing = $this->em->getRepository(Hostel::class)
                ->findOneBy(['hostelName' => $hostelData['name']]);

            if (!$existing) {
                $hostel = new Hostel();
                $hostel->setName($hostelData['name']);
                $hostel->setCategory($hostelData['category']);
                $hostel->setHostelType($hostelData['type']);
                $this->em->persist($hostel);
            }
        }

        $this->em->flush();

        echo "Hostels seeded successfully!\n";
    }
}
