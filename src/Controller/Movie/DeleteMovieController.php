<?php

namespace App\Controller\Movie;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteMovieController extends AbstractController
{
    public function __construct(
        private readonly MovieRepository $movieRepository,
    )
    {
    }

    #[Route('/movie/delete/{id}', name: 'app_movie_delete')]
    #[IsGranted('delete', subject: 'movie')]
    public function __invoke(Movie $movie): Response
    {
        $this->movieRepository->remove($movie, true);

        $this->addFlash('success', 'Movie deleted successfully.');

        return $this->redirectToRoute('app_movie_list');
    }
}
