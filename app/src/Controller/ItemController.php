<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ApiService;
use Symfony\UX\Turbo\TurboStreamResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ItemController extends AbstractController
{
    private ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    #[Route('/todolists/{idList<\d+>}/items', name: 'app_items_create', methods: ['POST'])]
    function create(Request $request, string $idList): Response
    {
        $title = $request->request->get('title');
        if (!$title) {
            return new JsonResponse(['error' => 'Missing title'], Response::HTTP_BAD_REQUEST);
        }

        $item = $this->apiService->post('api_items_create', ['idList' => $idList], [
            'json' => ['title' => $title]
        ]);

        $todolist = $this->apiService->get('api_todolists_get', ['id' => $idList]);

        return $this->render('list/_detail_stream.html.twig', [
            'todolist' => $todolist
        ], new TurboStreamResponse());
    }

    #[Route('/items/{id<\d+>}/sub-items', name: 'app_subitems_create', methods: ['POST'])]
    function createSubItem(Request $request, string $id): Response
    {
        $title = $request->request->get('title');
        if (!$title) {
            return new JsonResponse(['error' => 'Missing title'], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->apiService->post('api_subitems_create', ['id' => $id], [
            'json' => ['title' => $title]
        ]);

        $idTodolist = $data['parent'] ? $data['parent']['todoList']['id'] : $data['item']['todoList']['id'];

        $todolist = $this->apiService->get('api_todolists_get', ['id' => $idTodolist]);

        return $this->render('list/_detail_stream.html.twig', [
            'todolist' => $todolist
        ], new TurboStreamResponse());
    }

    #[Route('/items/{id<\d+>}', name: 'app_items_update', methods: ['PUT'])]
    function update(string $id, Request $request): Response
    {
        $title = $request->request->get('title');
        if (!$title) {
            return new JsonResponse(['error' => 'Missing title'], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->apiService->put('api_items_update', ['id' => $id], [
            'json' => ['title' => $title]
        ]);

        $idTodolist = $data['parent'] ? $data['parent']['todoList']['id'] : $data['item']['todoList']['id'];

        $todolist = $this->apiService->get('api_todolists_get', ['id' => $idTodolist]);

        return $this->render('list/_detail_stream.html.twig', [
            'todolist' => $todolist
        ], new TurboStreamResponse());
    }

    #[Route('/items/{id<\d+>}', name: 'app_items_delete', methods: ['DELETE'])]
    function delete(string $id): Response
    {
        $data = $this->apiService->delete('api_items_delete', ['id' => $id]);

        $idTodolist = $data['parent'] ? $data['parent']['todoList']['id'] : $data['item']['todoList']['id'];

        $todolist = $this->apiService->get('api_todolists_get', ['id' => $idTodolist]);

        return $this->render('list/_detail_stream.html.twig', [
            'todolist' => $todolist
        ], new TurboStreamResponse());
    }
}
