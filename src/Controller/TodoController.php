<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoFormType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('todo/index.html.twig');
    }

    #[Route('/todos', name: 'app_todos'), IsGranted('IS_AUTHENTICATED')]
    public function todosPage(Request $request, ManagerRegistry $doctrine): Response
    {
        $todos = $this->getUser()->getTodos();

        $form = $this->createForm(TodoFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($this->getUser()){
                $entityManager = $doctrine->getManager();

                $todo = new Todo();
                $todo->setName($form->get('name')->getData());
                $todo->setDescription($form->get('description')->getData());
                $todo->setDate($form->get('date')->getData());
                $todo->setIsPeriod($form->get('is_period')->getData());
                $todo->setPeriodFrom($form->get('period_from')->getData());
                $todo->setPeriodTo($form->get('period_to')->getData());
                $todo->setPeriodTime($form->get('period_time')->getData());
                $todo->setUser($this->getUser());

                $entityManager->persist($todo);
                $entityManager->flush();

                return $this->redirectToRoute('app_todos');
            }
        }

        return $this->renderForm('todo/todos.html.twig', [
            'todos' => $todos,
            'addTaskForm' => $form
        ]);
    }

    #[Route('/todo/{id}', name: 'app_todo'), IsGranted('IS_AUTHENTICATED')]
    public function todoPage($id)
    {
        return $this->render('todo/todo.html.twig', [
        'id' => $id
        ]);
    }
}
