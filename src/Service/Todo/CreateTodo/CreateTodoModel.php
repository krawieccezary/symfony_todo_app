<?php

declare(strict_types=1);

namespace App\Service\Todo\CreateTodo;

use App\Entity\Priority;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class CreateTodoModel
{
    private function __construct(
        private string              $name,
        private string              $description,
        private \DateTimeInterface  $date,
        private Priority            $priority,
        private bool                $isPeriod,
        private ?\DateTimeInterface $periodFrom,
        private ?\DateTimeInterface $periodTo,
        private ?\DateTimeInterface $periodTime,
        private User                $user
    )
    {
    }

    public static function createTodo(
        string              $name,
        string              $description,
        \DateTimeInterface  $date,
        Priority            $priority,
        bool                $isPeriod,
        ?\DateTimeInterface $periodFrom,
        ?\DateTimeInterface $periodTo,
        ?\DateTimeInterface $periodTime,
        UserInterface       $user
    ): self
    {
        return new self(
            $name,
            $description,
            $date,
            $priority,
            $isPeriod,
            $periodFrom,
            $periodTo,
            $periodTime,
            $user
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @return Priority
     */
    public function getPriority(): Priority
    {
        return $this->priority;
    }

    /**
     * @return bool
     */
    public function isPeriod(): bool
    {
        return $this->isPeriod;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getPeriodFrom(): ?\DateTimeInterface
    {
        return $this->periodFrom;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getPeriodTo(): ?\DateTimeInterface
    {
        return $this->periodTo;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getPeriodTime(): ?\DateTimeInterface
    {
        return $this->periodTime;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}