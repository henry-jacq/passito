<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateSuperAdminCommand extends Command
{
    protected static $defaultName = 'app:create-super-admin';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(self::$defaultName);
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create a super admin user')
            ->addArgument('email', InputArgument::REQUIRED, 'The email address of the super admin')
            ->addArgument('password', InputArgument::REQUIRED, 'The password for the super admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        // Check if a user with the same email already exists
        $existingUser = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if ($existingUser) {
            $io->error('A user with this email already exists.');
            return Command::FAILURE;
        }

        // Create the super admin user
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT, ['cost' => 12])); // Secure password hashing
        $user->setRole('super_admin');
        $user->setCreatedAt(new \DateTime());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Super admin user created successfully.');

        return Command::SUCCESS;
    }
}
