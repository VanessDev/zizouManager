<?php

namespace App\Controller;

use App\Entity\TodoItem;
use App\Form\TodoItemType;
use App\Repository\TodoItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/todo/item')]
final class TodoItemController extends AbstractController
{
    #[Route(name: 'app_todo_item_index', methods: ['GET'])]
    public function index(TodoItemRepository $todoItemRepository): Response
    {
        return $this->render('todo_item/index.html.twig', [
            'todo_items' => $todoItemRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_todo_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todoItem = new TodoItem();
        $form = $this->createForm(TodoItemType::class, $todoItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($todoItem);
            $entityManager->flush();

            return $this->redirectToRoute('app_todo_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('todo_item/new.html.twig', [
            'todo_item' => $todoItem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_todo_item_show', methods: ['GET'])]
    public function show(TodoItem $todoItem): Response
    {
        return $this->render('todo_item/show.html.twig', [
            'todo_item' => $todoItem,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_todo_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TodoItem $todoItem, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TodoItemType::class, $todoItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_todo_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('todo_item/edit.html.twig', [
            'todo_item' => $todoItem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_todo_item_delete', methods: ['POST'])]
    public function delete(Request $request, TodoItem $todoItem, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$todoItem->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($todoItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_todo_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
