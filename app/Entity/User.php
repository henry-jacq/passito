<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\Gender;
use App\Enum\UserRole;
use App\Enum\UserStatus;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'users', indexes: [
    new ORM\Index(name: "email_idx", columns: ["email"]),
    new ORM\Index(name: "gender_idx", columns: ["gender"])
])]
class User
{
    use EntityGetSetTrait;

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
