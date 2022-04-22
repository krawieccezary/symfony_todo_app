<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\User\CreateUser\CreateUserModel;
use App\Service\User\CreateUser\CreateUserService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mime\Address;

class RegisterUser
{

    public function __construct(
        private UserRepository        $userRepository,
        private CreateUserService     $createUserService,
        private EmailVerifier         $emailVerifier,
        private ParameterBagInterface $parameterBag
    )
    {
    }

    public function execute(
        string $username,
        string $name,
        string $surname,
        string $email,
        int    $phone,
        string $plainPassword
    ): User
    {
        if ($this->userRepository->isEmailExists($email)) {
            throw new \Exception('Użytkownik o podanym adresie e-mail już istnieje. Podaj inny e-mail');
        }

        $user = $this->createUserService->create(
            CreateUserModel::createUser(
                $username,
                $name,
                $surname,
                $email,
                $phone,
                $plainPassword
            )
        );

        $this->userRepository->add($user);

        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address($this->parameterBag->get('app_email_sender'), 'Todo App'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );

        return $user;
    }
}