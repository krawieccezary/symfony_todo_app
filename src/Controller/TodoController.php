<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoFormType;
use App\Repository\TodoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    private $entityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('todo/index.html.twig');
    }

    #[Route('/todos', name: 'app_todos'), IsGranted('IS_AUTHENTICATED')]
    public function todosPage(Request $request): Response
    {
        $todos = $this->getUser()->getTodos();

        $form = $this->createForm(TodoFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($this->getUser()){

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
        }

        return $this->renderForm('todo/todos.html.twig', [
            'todos' => $todos,
            'addTaskForm' => $form
        ]);
    }

    #[Route('/todo/{id}', name: 'app_todo'), IsGranted('IS_AUTHENTICATED')]
    public function todoPage($id, TodoRepository $todoRepository, Request $request)
    {
        $todo = $todoRepository->find($id);
        $form = $this->createForm(TodoFormType::class, $todo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try {
                $this->entityManager->persist($todo);
                $this->entityManager->flush();
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
}
