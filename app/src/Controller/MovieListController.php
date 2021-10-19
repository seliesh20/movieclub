<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\MovieList;
use App\Repository\MovieListRepository;

class MovieListController extends AbstractController
{
    #[Route('/api/check_movie', name: 'movie_list')]
    public function index(
        Request $request, 
        MovieListRepository $movieListRepository): Response
    {
        $movies = $movieListRepository->findBy([
            'imdb_id' => $request->get('imdbId')
        ]);
        if(!(is_array($movies) && count($movies))){
            return new JsonResponse(['status'=>'success']);
        } 
        return new JsonResponse(['status'=>'failure', 'movies'=>$movies]);
    }
}
