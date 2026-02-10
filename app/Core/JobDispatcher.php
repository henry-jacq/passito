<?php

namespace App\Core;

use App\Entity\Job;
use RuntimeException;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class JobDispatcher
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {}

    /**
     * Dispatch a job into the queue.
     *
     * @param string                 $jobClass     Fully qualified job class name (must exist).
     * @param JobPayloadBuilder      $payload      The payload for the job.
     * @param DateTimeInterface|null $availableAt  When the job becomes eligible.
     *
     * @return Job
     */
    public function dispatch(
        string $jobClass,
        JobPayloadBuilder $payload,
        ?DateTimeInterface $availableAt = null
    ): Job {
        if (!class_exists($jobClass)) {
            throw new RuntimeException("Job class {$jobClass} not found");
        }

        $job = new Job($jobClass, $payload->getPayload());
        $job->setStatus('pending');
        $job->setAvailableAt($availableAt ?? new \DateTimeImmutable());

        $this->em->persist($job);
        $this->em->flush();

        return $job;
    }
}
