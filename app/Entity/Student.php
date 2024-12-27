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
    private DateTime $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getHostel(): Hostel
    {
        return $this->hostel;
    }

    public function setHostel(Hostel $hostel): void
    {
        $this->hostel = $hostel;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDigitalId(): int
    {
        return $this->digitalId;
    }

    public function setDigitalId(int $digitalId): void
    {
        $this->digitalId = $digitalId;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getBranch(): string
    {
        return $this->branch;
    }

    public function setBranch(string $branch): void
    {
        $this->branch = $branch;
    }

    public function getRoomNo(): string
    {
        return $this->roomNo;
    }

    public function setRoomNo(string $roomNo): void
    {
        $this->roomNo = $roomNo;
    }

    public function getParentNo(): string
    {
        return $this->parentNo;
    }

    public function setParentNo(string $parentNo): void
    {
        $this->parentNo = $parentNo;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
