<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\TodoList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TodoListService
{
    private EntityManagerInterface $entityManager;
    private NormalizerInterface $normalizer;

    public function __construct(EntityManagerInterface $entityManager, NormalizerInterface $normalizer)
    {
        $this->entityManager = $entityManager;
        $this->normalizer = $normalizer;
    }

    public function list(): array
    {
        $todolists = $this->entityManager->getRepository(TodoList::class)->findAll();

        // Normalize each object in the array
        return array_map(
            fn($todolist) =>
            $this->normalizer->normalize($todolist, null, ['groups' => 'todo_list']),
            $todolists
        );
    }

    public function get(string $id): array
    {
        $todolist = $this->entityManager->getRepository(TodoList::class)->find($id);

        return $this->normalizer->normalize($todolist, null, ['groups' => 'todo_list']);
    }

    public function create(string $name): array
    {
        $todolist = new TodoList();
        $todolist->setName($name);
        $now = new DateTimeImmutable();
        $todolist->setCreatedAt($now)
            ->setUpdatedAt($now);

        $user = new User();
        $user->setId('1')
            ->setUsername('Salma')
            ->setEmail('Salma@bha.com')
            ->setPassword('Password')
            ->setCreatedAt($now);

        $todolist->setUser($user);

        $this->entityManager->persist($user);
        $this->entityManager->persist($todolist);
        $this->entityManager->flush();

        return $this->normalizer->normalize($todolist, null, ['groups' => 'todo_list']);
    }

    public function update(string $id, string $name): array
    {
        $todolist = $this->entityManager->getRepository(TodoList::class)->find($id);

        $now = new DateTimeImmutable();
        $todolist->setName($name)
            ->setUpdatedAt($now);

        $this->entityManager->persist($todolist);
        $this->entityManager->flush();

        return $this->normalizer->normalize($todolist, null, ['groups' => 'todo_list']);
    }

    public function delete(string $id): void
    {
        $todolist = $this->entityManager->getRepository(TodoList::class)->find($id);
        $this->entityManager->remove($todolist);
        $this->entityManager->flush();
    }
}
