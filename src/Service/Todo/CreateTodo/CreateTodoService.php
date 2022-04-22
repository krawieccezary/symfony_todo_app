<?php

declare(strict_types=1);

namespace App\Service\Todo\CreateTodo;

use App\Entity\Todo;
use App\Repository\TodoRepository;

class CreateTodoService
{
    public function __construct(private TodoRepository $todoRepository)
    {
    }

    public function create(CreateTodoModel $createTodoModel): void
    {
        $todo = $this->createTodoInstance($createTodoModel);

        if ($createTodoModel->isPeriod()) {
            $periodDay = $createTodoModel->getPeriodFrom();
            $periodTo = $createTodoModel->getPeriodTo();

            while ($periodDay <= $periodTo) {
                $todo = $this->createTodoInstance($createTodoModel);
                $todo->setDate($periodDay);

                $this->todoRepository->add($todo);
                $periodDay->modify('+1 day');
            }
        } else {
            $this->todoRepository->add($todo);
        }
    }

    private function createTodoInstance(CreateTodoModel $createTodoModel): Todo
    {
        $todo = new Todo();
        $todo
            ->setName($createTodoModel->getName())
            ->setDescription($createTodoModel->getDescription())
            ->setDate($createTodoModel->getDate())
            ->setPriority($createTodoModel->getPriority())
            ->setIsPeriod($createTodoModel->isPeriod())
            ->setPeriodFrom($createTodoModel->getPeriodFrom())
            ->setPeriodTo($createTodoModel->getPeriodTo())
            ->setPeriodTime($createTodoModel->getPeriodTime())
            ->setUser($createTodoModel->getUser());

        return $todo;
    }
}