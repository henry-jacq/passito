<?php

namespace App\Core;

use App\Entity\Job;
use RuntimeException;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class JobDispatcher
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function dispatch(string $jobClass, array $payload = [], ?DateTimeInterface $availableAt = null): void
    {
        if (!class_exists($jobClass)) {
            throw new RuntimeException("Job class {$jobClass} not found");
        }

        $job = new Job($jobClass, $payload);
        $job->setStatus('pending');
        $job->setAvailableAt($availableAt ?? new \DateTime());

        $this->em->persist($job);
        $this->em->flush();
    }
}
