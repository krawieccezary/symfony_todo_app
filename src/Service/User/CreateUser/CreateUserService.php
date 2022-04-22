<?php

declare(strict_types=1);

namespace App\Service\User\CreateUser;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserService
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {}

    public function create(CreateUserModel $createUserModel): User
    {
        $user = new User();
        $user
            ->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $createUserModel->getPlainPassword()
                )
            )
            ->setRoles($user->getRoles())
            ->setUsername($createUserModel->getUsername())
            ->setName($createUserModel->getName())
            ->setSurname($createUserModel->getSurname())
            ->setEmail($createUserModel->getEmail())
            ->setPhone($createUserModel->getPhone());

        return $user;
    }
}