<?php

declare(strict_types=1);

namespace App\Entity;

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
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    #[ORM\Column(type: 'string', length: 64)]
    private string $name;

    #[ORM\Column(type: 'integer', unique: true)]
    private int $digitalId;

    #[ORM\Column(type: 'integer')]
    private int $year;

    #[ORM\Column(type: 'string', length: 32)]
    private string $branch;

    #[ORM\Column(type: 'string', length: 8)]
    private string $roomNo;

    #[ORM\Column(type: 'string', length: 20)]
    private string $parentNo;

    #[ORM\Column(type: 'string', length: 128)]
    private string $institution;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true, onUpdate: "CURRENT_TIMESTAMP")]
    private \DateTime $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDigitalId()
    {
        return $this->digitalId;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getBranch()
    {
        return $this->branch;
    }

    public function getRoomNo()
    {
        return $this->roomNo;
    }

    public function getParentNo()
    {
        return $this->parentNo;
    }

    public function getInstitution()
    {
        return $this->institution;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setDigitalId($digitalId)
    {
        $this->digitalId = $digitalId;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    public function setRoomNo($roomNo)
    {
        $this->roomNo = $roomNo;
    }

    public function setParentNo($parentNo)
    {
        $this->parentNo = $parentNo;
    }

    public function setInstitution($institution)
    {
        $this->institution = $institution;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function __toArray()
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'name' => $this->name,
            'digitalId' => $this->digitalId,
            'year' => $this->year,
            'branch' => $this->branch,
            'roomNo' => $this->roomNo,
            'parentNo' => $this->parentNo,
            'institution' => $this->institution,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
