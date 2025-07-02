<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Settings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MaintenanceModeCommand extends Command
{
    protected static $defaultName = 'app:maintenance';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(self::$defaultName);
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:maintenance')
            ->setDescription('Check or toggle maintenance mode')
            ->addOption('status', null, InputOption::VALUE_NONE, 'Show current maintenance mode status')
            ->addOption('toggle', null, InputOption::VALUE_NONE, 'Toggle maintenance mode (on/off)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $key = 'maintenance_mode';

        $settings = $this->entityManager
            ->getRepository(Settings::class)
            ->findOneBy(['keyName' => $key]);

        if (!$settings) {
            $settings = new Settings();
            $settings->setKeyName($key);
            $settings->setValue('false');
            $this->entityManager->persist($settings);
            $this->entityManager->flush();
        }

        $currentMode = $settings->getValue();

        if ($input->getOption('status')) {
            $io->info('Need to inform the users about the maintenance mode status by sending email');

            $io->info('Maintenance mode is ' . ($currentMode === 'true' ? 'enabled' : 'disabled'));
            return Command::SUCCESS;
        }

        if ($input->getOption('toggle')) {
            $newValue = $currentMode === 'true' ? 'false' : 'true';
            $settings->setValue($newValue);
            $this->entityManager->flush();

            if ($newValue == 'true') {
                $io->success('Enabled Maintenance mode!');
                return Command::SUCCESS;
            } else {
                $io->success('Disabled Maintenance mode!');
                return Command::SUCCESS; 
            }
        }

        $io->warning('No action taken. Use --status or --toggle.');
        return Command::INVALID;
    }
}
