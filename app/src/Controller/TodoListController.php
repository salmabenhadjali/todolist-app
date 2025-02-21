<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TodoListController extends AbstractController
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/', name: 'app_todolists_all')]
    function homepage(): Response
    {
        $path = $this->generateUrl('api_todolists_get_all', [], UrlGeneratorInterface::ABSOLUTE_PATH);
        $url = 'http://nginx' . $path;

        $response = $this->httpClient->request('GET', $url);
        $todoLists = $response->toArray();

        return $this->render('list/homepage.html.twig', [
            'lists' => $todoLists
        ]);
    }

    #[Route('/todolists/{id<\d+>}', name: 'app_todolists_detail')]
    function itemPage(Request $request, string $id): Response
    {

        $path = $this->generateUrl('api_todolists_get', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_PATH);
        $url = 'http://nginx' . $path;

        $response = $this->httpClient->request('GET', $url);
        $todoList = $response->toArray();

        return $this->render('list/details.html.twig', [
            'todoList' => $todoList
        ]);
    }
}
