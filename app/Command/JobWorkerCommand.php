<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Job;
use App\Interfaces\JobInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class JobWorkerCommand extends Command
{
    protected static $defaultName = 'jobs:work';
    protected static $defaultDescription = 'Start Job Worker for dispatched jobs.';

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ContainerInterface $container,
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Continuously polls the jobs table and executes pending jobs.')
            ->setDescription('Continuously polls the jobs table and executes pending jobs.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Define custom console styles
        $output->getFormatter()->setStyle('info', new OutputFormatterStyle('cyan'));
        $output->getFormatter()->setStyle('done',   new OutputFormatterStyle('green', null, ['bold']));
        $output->getFormatter()->setStyle('warn', new OutputFormatterStyle('yellow', null, ['bold']));
        $output->getFormatter()->setStyle('fail', new OutputFormatterStyle('red', null, ['bold']));

        while (true) {
            $job = $this->fetchNextJob();
            if ($job && $this->canRun($job)) {
                $this->processJob($job, $output);
            } else {
                sleep(2);
            }
        }

        return Command::SUCCESS;
    }

    private function fetchNextJob(): ?Job
    {
        return $this->em->getRepository(Job::class)
            ->createQueryBuilder('j')
            ->where('j.status = :status')
            ->andWhere('j.availableAt <= :now')
            ->setParameter('status', 'pending')
            ->setParameter('now', new \DateTimeImmutable())
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function processJob(Job $job, OutputInterface $output): void
    {
        $job->setStatus('processing');
        $this->em->flush();

        $startTime = (new \DateTimeImmutable())->format('H:i:s');
        $this->printLine($output, 'INFO', "Processing Job #{$job->getId()} ({$job->getType()})", $startTime);

        try {
            // Base payload
            $payload = $job->getPayload();

            // Merge dependency results
            if (!empty($job->getDependencies())) {
                foreach ($job->getDependencies() as $depId) {
                    $dep = $this->em->find(Job::class, $depId);

                    if ($dep && $dep->getStatus() === 'done' && $dep->getResult()) {
                        // Namespace each dependency by job ID
                        $payload['dependencies'][(string)$depId] = $dep->getResult();
                    }
                }
            }

            /** @var JobInterface $handler */
            $handler = $this->container->get($job->getType());
            $result = $handler->handle($payload);

            // Save result if provided
            if (is_array($result)) {
                $job->setResult($result);
            }

            $job->setStatus('done');
            $this->em->flush();

            $endTime = (new \DateTimeImmutable())->format('H:i:s');
            $this->printLine($output, 'DONE', "Completed Job #{$job->getId()} ({$job->getType()})", $endTime);
        } catch (\Throwable $e) {
            $job->incrementAttempts();
            $endTime = (new \DateTimeImmutable())->format('H:i:s');

            if ($job->getAttempts() >= $job->getMaxAttempts()) {
                $job->setStatus('failed');
                $job->setLastError($e->getMessage());
                $this->printLine(
                    $output,
                    'FAIL',
                    "Job Failed #{$job->getId()} ({$job->getType()}): {$e->getMessage()}",
                    $endTime
                );
            } else {
                $job->setStatus('pending');
                $job->setLastError($e->getMessage());
                $this->printLine(
                    $output,
                    'WARN',
                    "Job Failed #{$job->getId()}, will retry. Error: {$e->getMessage()}",
                    $endTime
                );
            }

            $this->em->flush();
        }
    }

    private function canRun(Job $job): bool
    {
        foreach ($job->getDependencies() as $depId) {
            $dep = $this->em->getRepository(Job::class)->find($depId);
            if (!$dep || $dep->getStatus() !== 'done') {
                return false; // wait until dependency is done
            }
        }
        return true;
    }

    /**
     * Print a line with dots filling space between message and timestamp
     */
    private function printLine(OutputInterface $output, string $label, string $message, ?string $time = null): void
    {
        $width = (int) exec('tput cols') ?: 80;
        $time = $time ?? (new \DateTimeImmutable())->format('H:i:s');

        $text = "[$label] $message";
        $dotsCount = max(2, $width - strlen(strip_tags($text)) - strlen($time) - 1);
        $dots = str_repeat('.', $dotsCount);

        $styleMap = [
            'INFO' => 'info',
            'DONE'   => 'done',
            'WARN' => 'warn',
            'FAIL' => 'fail',
        ];

        $style = $styleMap[$label] ?? 'info';
        $output->writeln(sprintf('<%s>%s%s%s</%s>', $style, $text, $dots, $time, $style));
    }
}
