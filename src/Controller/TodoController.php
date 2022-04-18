<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoFormType;
use App\Repository\TodoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    private ObjectManager $entityManager;
    private TodoRepository $todoRepository;

    public function __construct(ManagerRegistry $doctrine, TodoRepository $todoRepository)
    {
        $this->entityManager = $doctrine->getManager();
        $this->todoRepository = $todoRepository;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('todo/index.html.twig');
    }

    #[Route('/todos', name: 'app_todos')]
    public function todosPage(Request $request): Response
    {
        $todos = $this->todoRepository->findBy(
            ['user' => $this->getUser()],
            ['date' => 'ASC']
        );

        $form = $this->createForm(TodoFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try {
                $todo = new Todo();
                $todo->setName($form->get('name')->getData());
                $todo->setDescription($form->get('description')->getData());
                $todo->setDate($form->get('date')->getData());
                $todo->setPriority($form->get('priority')->getData());
                $todo->setIsPeriod($form->get('is_period')->getData());
                $todo->setPeriodFrom($form->get('period_from')->getData());
                $todo->setPeriodTo($form->get('period_to')->getData());
                $todo->setPeriodTime($form->get('period_time')->getData());
                $todo->setUser($this->getUser());

                $this->entityManager->persist($todo);
                $this->entityManager->flush();

                $this->addFlash('sukces', 'Dodano nowe zadanie!');
            } catch (\Exception $exception) {
                $this->addFlash('Błąd', 'Wystąpił problem. Zadanie nie zostało dodane.');
            }

            return $this->redirectToRoute('app_todos');
        }

        return $this->renderForm('todo/todos.html.twig', [
            'todos' => $todos,
            'addTaskForm' => $form
        ]);
    }

    #[Route('/todo/{id}', name: 'app_todo', methods: ['GET'])]
    public function todoPage(int $id, Request $request)
    {
        $todo = $this->todoRepository->find($id);
        $form = $this->createForm(TodoFormType::class, $todo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try {
                $this->entityManager->persist($todo);
                $this->addFlash('sukces', 'Zadanie zostało zaktualizowane.');
            } catch (\Exception $exception) {
                $this->addFlash('błąd', 'Wystąpił problem. Zadanie nie zostało zaktualizowane.');
            }
            return $this->redirectToRoute('app_todo', ['id' => $todo->getId()]);
        }

        return $this->renderForm('todo/todo.html.twig', [
            'todo' => $todo,
            'form' => $form
        ]);
    }

    #[Route('/todo/remove/{id}', name: 'app_remove_todo', methods: ['GET'])]
    public function removeTodo(int $id): Response
    {
        $todo = $this->todoRepository->find($id);

        try {
            $this->entityManager->remove($todo);
            $this->addFlash('sukces', 'Zadanie pomyślnie usunięto.');
        } catch (\Exception $exception) {
            $this->addFlash('sukces', 'Wystąpił problem. Zadanie nie zostało usunięte.');
        }

        return $this->redirectToRoute('app_todos');
    }

    #[Route('/todo/completed/{id}', name: 'app_completed_todo', methods: ['GET'])]
    public function setIsCompletedTodo(int $id): Response
    {
        $todo = $this->todoRepository->find($id);

        try {
            $todo->setIsCompleted(true);
            $this->entityManager->persist($todo);
        } catch (\Exception $exception) {
            $this->addFlash('błąd', 'Wystąpił problem. Nie udało się zaznaczyć zadania.');
        }

        return $this->redirectToRoute('app_todos');
    }

    #[Route('/todo/uncompleted/{id}', name: 'app_uncompleted_todo', methods: ['GET'])]
    public function setIsUncompletedTodo(int $id): Response
    {
        $todo = $this->todoRepository->find($id);

        try {
            $todo->setIsCompleted(false);
            $this->entityManager->persist($todo);
        } catch (\Exception $exception) {
            $this->addFlash('błąd', 'Wystąpił problem. Nie udało się odznaczyć zadania.');
        }

        return $this->redirectToRoute('app_todos');
    }
}
