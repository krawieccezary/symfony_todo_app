<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function welcomePage(): Response
    {
        $todos = $this->getUser()->getTodos();
        return $this->render('todo/todos.html.twig', [
            'todos' => $todos
        ]);
    }
}
