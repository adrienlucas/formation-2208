<?php

namespace App\Controller\Movie;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoadMovieController extends AbstractController
{
    public function __construct(
        private readonly MovieRepository $movieRepository,
        private readonly GenreRepository $genreRepository,
    )
    {
    }

    #[Route('/movie/load', name: 'app_movie_load')]
    public function index(): Response
    {
        $genreAction = new Genre();
        $genreAction->setName('Action');
        $this->genreRepository->add($genreAction);

        $genreSciFi = new Genre();
        $genreSciFi->setName('Science-fiction');
        $this->genreRepository->add($genreSciFi);

        $movie = new Movie();
        $movie->setTitle('The Matrix');
        $movie->setDescription('Neo learns kung-fu.');
        $movie->addGenre($genreAction);
        $movie->addGenre($genreSciFi);

        $this->movieRepository->add($movie, true);

        return new Response('OK');
    }
}