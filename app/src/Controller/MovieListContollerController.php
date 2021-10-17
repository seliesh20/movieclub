<?php

namespace App\Controller;

use App\Entity\User;
use PHPUnit\Util\Json;
use ProxyManager\Factory\RemoteObject\Adapter\JsonRpc;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieListContollerController extends AbstractController
{
    #[Route('/api/movielist', name: 'list_movie_list', methods:['post'])]
    public function index(UserInterface $user): Response
    {
        return new JsonResponse(['username' => $user->getUserIdentifier()]);
    }
}
