<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'students')]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Hostel::class)]
    #[ORM\JoinColumn(name: 'hostel_id', referencedColumnName: 'id', nullable: false)]
    private Hostel $hostel;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'integer', unique: true)]
    private int $digitalId;

    #[ORM\Column(type: 'integer')]
    private int $year;

    #[ORM\Column(type: 'string', length: 255)]
    private string $branch;

    #[ORM\Column(type: 'string', length: 255)]
    private string $roomNo;

    #[ORM\Column(type: 'string', length: 15)]
    private string $parentNo;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    private DateTime $updatedAt;
}
