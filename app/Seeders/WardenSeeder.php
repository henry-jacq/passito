<?php

namespace App\Seeders;

use DateTime;
use App\Entity\User;
use App\Enum\Gender;
use App\Enum\UserRole;
use Doctrine\ORM\EntityManagerInterface;

class WardenSeeder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function run()
    {
        $wardens = [
            ['name' => 'Leo Das', 'email' => 'leo@gmail.com', 'contact' => '1234567890', 'gender' => Gender::MALE],
            ['name' => 'Priya', 'email' => 'priya@gmail.com', 'contact' => '4567891230', 'gender' => Gender::FEMALE]
        ];

        foreach ($wardens as $wardenData) {
            $existing = $this->em->getRepository(User::class)
                ->findOneBy(['email' => $wardenData['email']]);

            if (!$existing) {
                $warden = new User();
                $warden->setName($wardenData['name']);
                $warden->setEmail($wardenData['email']);
                $warden->setContactNo($wardenData['contact']);
                $warden->setPassword(password_hash($wardenData['contact'], PASSWORD_DEFAULT, ['cost' => 10]));
                $warden->setGender($wardenData['gender']);
                $warden->setRole(UserRole::ADMIN);
                $warden->setCreatedAt(new DateTime());
                $this->em->persist($warden);
            }
        }

        $this->em->flush();

        echo "Wardens seeded successfully!\n";
    }
}
