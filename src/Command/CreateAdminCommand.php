<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create an admin user',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Create admin user
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));

        $this->entityManager->persist($admin);

        // Create admin client profile
        $adminClient = new Client();
        $adminClient->setNom('Administrateur');
        $adminClient->setTelephone('+224 123 456 789');
        $adminClient->setUser($admin);

        $this->entityManager->persist($adminClient);

        // Create regular user
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user123'));

        $this->entityManager->persist($user);

        // Create user client profile
        $userClient = new Client();
        $userClient->setNom('Utilisateur Test');
        $userClient->setTelephone('+224 987 654 321');
        $userClient->setUser($user);

        $this->entityManager->persist($userClient);

        $this->entityManager->flush();

        $io->success('Admin and test users created successfully!');
        $io->note('Admin: admin@example.com / admin123');
        $io->note('User: user@example.com / user123');

        return Command::SUCCESS;
    }
}