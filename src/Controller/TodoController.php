<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TodoController extends AbstractController
{
    public function __construct(
        private Security $security,
    ) {
    }

    #[Route('/todo', name: 'getUserTodos', methods: ['GET'])]
    public function getTodos(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $repository = $em->getRepository(Todo::class);
        $todos = $repository->findBy(['owner' => 3]);

        $data = json_decode($serializer->serialize($todos, 'json'));

        return new JsonResponse(
            $data,
            Response::HTTP_OK
        );
    }

    #[Route('/todo', name: 'createTodo', methods: ['POST'])]
    public function createTodo(EntityManagerInterface $em, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $repository = $em->getRepository(User::class);
        $body = json_decode($request->getContent());

        // Replace with the logged in user
        $currentUser = $this->security->getUser();

        $newTodo = new Todo($body, $currentUser);

        $em->persist($newTodo);
        $em->flush();

        $data = json_decode($serializer->serialize($newTodo, 'json'));

        return new JsonResponse(
            [
                'newTodo' => $data
            ],
            Response::HTTP_CREATED
        );
    }
}
