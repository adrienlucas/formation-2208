<?php

namespace App\Controller\Movie;

use App\Entity\Movie;
use App\Gateway\OmdbGateway;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowMovieController extends AbstractController
{
    public function __construct(
        private readonly OmdbGateway $omdbGateway,
    )
    {
    }


    #[Route('/movie/show/{id}', name: 'app_movie_show')]
    public function __invoke(Movie $movie): Response
    {
        $moviePoster = $this->omdbGateway->getPosterByMovie($movie);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'movie_poster' => $moviePoster
        ]);
    }
}
