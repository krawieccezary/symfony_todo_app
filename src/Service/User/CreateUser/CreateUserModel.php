<?php

declare(strict_types=1);


namespace App\Service\User\CreateUser;

use App\Entity\User;

class CreateUserModel
{
    private function __construct(
        private string $username,
        private string $name,
        private string $surname,
        private string $email,
        private int    $phone,
        private string $plainPassword,
        private array  $roles
    )
    {
    }


    public static function createUser(
        string $username,
        string $name,
        string $surname,
        string $email,
        int    $phone,
        string $plainPassword
    ): self
    {
        return new self(
            $username,
            $name,
            $surname,
            $email,
            $phone,
            $plainPassword,
            [User::ROLE_USER]
        );
    }


    public function getUsername(): string
    {
        return $this->username;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): int
    {
        return $this->phone;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function getRoles(): string
    {
        return $this->roles;
    }
}