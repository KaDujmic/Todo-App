<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AuthController extends AbstractController
{
  public function __construct(
    private UserRepository $userRepository,
    private Security $security,
    private SerializerInterface $serializer
  ) {
  }

  #[Route('/login', name: 'login', methods: ['POST'])]
  public function getTodos(Request $request): JsonResponse
  {
    $currentUser = $this->security->getUser();
    $user = $this->serializer->serialize($currentUser, 'json');

    return new JsonResponse([
      'user' => $user,
    ], Response::HTTP_OK);
  }

  #[Route('/register', name: 'register', methods: ['POST'])]
  public function register(Request $request): JsonResponse
  {
    $jsonData = json_decode($request->getContent());
    $user = $this->userRepository->register($jsonData);

    return new JsonResponse([
      'user' => json_decode($this->serializer->serialize($user, 'json')),
    ], Response::HTTP_CREATED);
  }
}
