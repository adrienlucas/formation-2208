<?php

namespace App\Controller\Movie;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowMovieController extends AbstractController
{
    #[Route('/movie/show/{id}', name: 'app_movie_show')]
    public function __invoke(Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }
}
