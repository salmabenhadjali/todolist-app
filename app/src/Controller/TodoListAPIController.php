<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\TodoList;
use Psr\Log\LoggerInterface;
use App\Service\TodoListService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TodoListAPIController extends AbstractController
{
    private LoggerInterface $logger;
    private TodoListService $todoListService;

    public function __construct(LoggerInterface $logger, TodoListService $todoListService)
    {
        $this->logger = $logger;
        $this->todoListService = $todoListService;
    }

    #[Route('/api/todolists', methods: ['GET'], name: 'api_todolists_get_all')]
    public function list(): Response
    {
        $todolists = $this->todoListService->list();

        return $this->json($todolists, Response::HTTP_OK);
    }

    #[Route('/api/todolists', methods: ['POST'], name: 'api_todolists_create')]
    public function create(Request $request): Response
    {
        $name = $request->request->get('name');

        if (!$name) {
            $this->logger->error('Invalid data for creating a TodoList');
            return $this->json(['message' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        };

        $todolist = $this->todoListService->create($name);

        $this->logger->info('Todolist cretaed with ID {id}', [
            'id' => $todolist->getId(),
        ]);

        return $this->json([
            'id' => $todolist->getId(),
            'message' => 'Todolost created successfully',
        ], Response::HTTP_CREATED);
    }

    #[Route('/api/todolists/{id<\d+>}', methods: ['GET'], name: 'api_todolists_get')]
    public function get(string $id): Response
    {
        $todolist = $this->todoListService->get($id);

        $this->logger->info("TodoList with ID {id}", [
            'data' => $todolist,
        ]);

        return $this->json($todolist);
    }

    #[Route('/api/todolists/{id<\d+>}', methods: ['PUT'], name: 'api_todolists_update')]
    public function update(int $id): Response
    {
        //TODO query to the database to create the current Todo List

        $todolist = [];
        $this->logger->info("TodoList updated with ID {id}", [
            'id' => $id,
        ]);

        return $this->json($todolist);
    }

    #[Route('/api/todolists/{id<\d+>}', methods: ['DELETE'], name: 'api_todolists_delete')]
    public function delete(int $id): Response
    {
        //TODO query to the database to delete the current Todo List

        $todolist = [];
        $this->logger->info("TodoList deleted with ID {id}", [
            'id' => $id,
        ]);

        return $this->json($todolist);
    }
}
