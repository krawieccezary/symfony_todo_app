<?php

namespace App\Command;

use App\Entity\User;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Utwórz nowego użytkownika',
)]
class NewUserCommand extends Command
{
    private $entityManager;
    private $userPasswordHasher;
    private $emailVerifier;

    public function __construct(string $name = null, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, EmailVerifier $emailVerifier)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->emailVerifier = $emailVerifier;

        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Pozwala dodać nowego użytkownika.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $io->ask('Podaj nazwę nowego użytkownika');
        $email = $io->ask('Podaj e-mail nowego użytkownika');

        while($this->entityManager->getRepository(User::class)->isEmailExists($email)){
            $email = $io->ask('Użytkownik o podanym adresie e-mail już istnieje. Podaj inny e-mail');
        };

        $name = $io->ask('Podaj imię nowego użytkownika');
        $surname = $io->ask('Podaj nazwisko nowego użytkownika');
        $phone = $io->ask('Podaj numer telefonu nowego użytkownika');
        $plainPassword = $io->askHidden('Podaj hasło nowego użytkownika');

        $user = new User();
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $plainPassword
            )
        );
        $user->setRoles($user->getRoles());
        $user->setUsername($username);
        $user->setName($name);
        $user->setSurname($surname);
        $user->setPhone($phone);
        $user->setEmail($email);

        $this->entityManager->persist($user);
        $this->entityManager->flush();


        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('web@cezarykrawiec.pl', 'Todo App'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
        // do anything else you need here, like send an email

        $io->success('Właśnie dodałeś nowego użytkownika. Możesz zalogować się do aplikacji za pomocą podanego adresu e-mail oraz hasła.');

        return Command::SUCCESS;
    }
}
