<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\Gender;
use App\Enum\UserRole;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'users', indexes: [
    new ORM\Index(name: "email_idx", columns: ["email"]),
    new ORM\Index(name: "gender_idx", columns: ["gender"])
])]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'string', enumType: UserRole::class)]
    private UserRole $role;

    #[ORM\Column(type: 'string', enumType: Gender::class)]
    private Gender $gender;

    #[ORM\Column(type: 'string', length: 32)]
    private string $contactNo;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\OneToMany(mappedBy: 'warden', targetEntity: Hostel::class)]
    private Collection $hostels;

    public function __construct()
    {
        $this->hostels = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): void
    {
        $this->role = $role;
    }
    
    public function getGender(): Gender
    {
        return $this->gender;
    }

    public function setGender(Gender $gender): void
    {
        $this->gender = $gender;
    }

    public function getContactNo(): string
    {
        return $this->contactNo;
    }

    public function setContactNo(string $contactNo): void
    {
        $this->contactNo = $contactNo;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getHostels(): Collection
    {
        return $this->hostels;
    }

    public function addHostel(Hostel $hostel): void
    {
        if (!$this->hostels->contains($hostel)) {
            $this->hostels->add($hostel);
            $hostel->setWarden($this);
        }
    }

    public function removeHostel(Hostel $hostel): void
    {
        if ($this->hostels->removeElement($hostel)) {
            if ($hostel->getWarden() === $this) {
                $hostel->setWarden(null);
            }
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'role' => $this->getRole(),
            'gender' => $this->getGender(),
            'contact_no' => $this->getContactNo(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s')
        ];
    }
}
