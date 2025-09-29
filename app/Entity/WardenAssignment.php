<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\AssignmentTarget;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'warden_assignments')]
class WardenAssignment
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'assigned_to', referencedColumnName: 'id', nullable: false)]
    private User $assignedTo;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'assigned_by', referencedColumnName: 'id', nullable: false)]
    private User $assignedBy;

    #[ORM\Column(type: 'string', enumType: AssignmentTarget::class, name: 'target_type', nullable: false)]
    private AssignmentTarget $targetType;

    // The actual entity ID corresponding to the enum (FK stored as plain int)
    #[ORM\Column(type: 'integer', name: 'assignment_id', nullable: false)]
    private int $assignmentId;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }
}
