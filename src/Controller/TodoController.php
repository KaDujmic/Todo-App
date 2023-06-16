<?php

namespace App\Controller;

use App\Entity\Todo;
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
        $currentUser = json_decode($serializer->serialize($this->security->getUser(), 'json'));
        $todos = $repository->findBy(['owner' => $currentUser->id]);

        $data = json_decode($serializer->serialize($todos, 'json'));

        return new JsonResponse(
            $data,
            Response::HTTP_OK
        );
    }

    #[Route('/todo', name: 'createTodo', methods: ['POST'])]
    public function createTodo(EntityManagerInterface $em, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $body = json_decode($request->getContent());
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
