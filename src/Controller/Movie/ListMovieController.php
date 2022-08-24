<?php

namespace App\Controller\Movie;

use App\Entity\Genre;
use App\Gateway\OmdbGateway;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ListMovieController extends AbstractController
{
    public function __construct(
        private readonly OmdbGateway $httpClient,
    )
    {
    }

    #[Route('/movie/list/{id}', name: 'app_movie_list', defaults: ['id' => null])]
    public function __invoke(MovieRepository $movieRepository, ?Genre $genre = null): Response
    {
        if($genre === null) {
            $movies = $movieRepository->findAll();
        } else {
            $movies = $movieRepository->findByGenre($genre);
        }

        return $this->render('movie/list.html.twig', [
            'movies' => $movies,
            'genre' => $genre
        ]);
    }
}
