<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'email_queue')]
class EmailQueue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $subject;

    #[ORM\Column(type: 'text')]
    private string $body;

    #[ORM\Column(type: 'string', length: 255)]
    private string $recipient;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $attachments = null; // Holds file paths or metadata

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $timeToSend;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    public function __construct(
        string $subject,
        string $body,
        string $recipient,
        ?array $attachments = null,
        ?\DateTimeInterface $timeToSend = null
    ) {
        $this->subject = $subject;
        $this->body = $body;
        $this->recipient = $recipient;
        $this->attachments = $attachments;
        $this->createdAt = new DateTimeImmutable();
        $this->timeToSend = $timeToSend ?? new DateTimeImmutable(); // Default to "now" if not provided
    }

    // Getters and setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): void
    {
        $this->recipient = $recipient;
    }

    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    public function setAttachments(?array $attachments): void
    {
        $this->attachments = $attachments;
    }

    public function getTimeToSend(): DateTimeInterface
    {
        return $this->timeToSend;
    }

    public function setTimeToSend(DateTimeInterface $timeToSend): void
    {
        $this->timeToSend = $timeToSend;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
