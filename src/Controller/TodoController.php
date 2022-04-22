<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoFormType;
use App\Repository\TodoRepository;
use App\Service\Todo\CreateTodo\CreateTodoModel;
use App\Service\Todo\CreateTodo\CreateTodoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{

    public function __construct(private TodoRepository $todoRepository, private CreateTodoService $createTodoService)
    {
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

        $todo = new Todo();
        $form = $this->createForm(TodoFormType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->createTodoService->create(CreateTodoModel::createTodo(
                    $form->get('name')->getData(),
                    $form->get('description')->getData(),
                    $form->get('date')->getData(),
                    $form->get('priority')->getData(),
                    $form->get('isPeriod')->getData(),
                    $form->get('periodFrom')->getData(),
                    $form->get('periodTo')->getData(),
                    $form->get('periodTime')->getData(),
                    $this->getUser()
                ));

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

    #[Route('/todo/{id}', name: 'app_todo')]
    public function todoPage(int $id, Request $request)
    {
        $todo = $this->todoRepository->find($id);
        $form = $this->createForm(TodoFormType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $isPeriod = $form->get('isPeriod')->getData();
                if ($isPeriod) {
                    $periodDay = $form->get('periodFrom')->getData();
                    $periodEnd = $form->get('periodTo')->getData();
                    $days = array();

                    while ($periodDay <= $periodEnd) {
//                        $todo->setDate($periodDay);
//                        $todo->setPeriodFrom($form->get('periodFrom')->getData());
//                        $todo->setPeriodTo($form->get('periodTo')->getData());
//                        $todo->setPeriodTime($form->get('periodTime')->getData());
//                        $periodDay = $periodDay->modify('+1 day');
//
//                        $this->entityManager->persist($todo);
//                        $this->entityManager->flush();

                        $periodDay = $periodDay->modify('+1 day');
                        $days[] = $periodDay;
                    }
                    dd($days);
                }


                $this->todoRepository->add($todo);
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
            $this->todoRepository->remove($todo);
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
            $this->todoRepository->persist($todo);
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
