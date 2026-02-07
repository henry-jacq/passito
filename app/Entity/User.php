<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\Gender;
use App\Enum\UserRole;
use App\Enum\UserStatus;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\Column(type: 'string', enumType: UserStatus::class)]
    private UserStatus $status = UserStatus::INACTIVE;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    public function __construct()
    {
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

    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    public function setStatus(UserStatus $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
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
            'status' => $this->getStatus(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s')
        ];
    }
}
