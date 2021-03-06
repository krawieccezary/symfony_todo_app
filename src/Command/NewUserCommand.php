<?php

namespace App\Command;

use App\Entity\User;
use App\UseCase\RegisterUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-user',
    description: 'Utwórz nowego użytkownika',
)]
class NewUserCommand extends Command
{

    public function __construct(private EntityManagerInterface $entityManager, private RegisterUser $registerUser, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Pozwala dodać nowego użytkownika.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $io->ask('Podaj nazwę nowego użytkownika');
        $email = $io->ask('Podaj e-mail nowego użytkownika');

        while ($this->entityManager->getRepository(User::class)->isEmailExists($email)) {
            $email = $io->ask('Użytkownik o podanym adresie e-mail już istnieje. Podaj inny e-mail');
        };

        $name = $io->ask('Podaj imię nowego użytkownika');
        $surname = $io->ask('Podaj nazwisko nowego użytkownika');
        $phone = $io->ask('Podaj numer telefonu nowego użytkownika');
        $plainPassword = $io->askHidden('Podaj hasło nowego użytkownika');

        $this->registerUser->execute(
            $username,
            $name,
            $surname,
            $email,
            $phone,
            $plainPassword
        );

        $io->success('Właśnie dodałeś nowego użytkownika. Możesz zalogować się do aplikacji za pomocą podanego adresu e-mail oraz hasła.');

        return Command::SUCCESS;
    }
}
