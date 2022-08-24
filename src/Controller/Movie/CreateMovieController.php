<?php

namespace App\Controller\Movie;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class CreateMovieController extends AbstractController
{
    public function __construct(
        private readonly MovieRepository $movieRepository,
        private readonly RouterInterface $router,
    )
    {
    }

    #[Route('/movie/create', name: 'app_movie_create')]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(MovieType::class, options: [
            'action' => $this->router->generate('app_movie_create')
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();
            $this->movieRepository->add($movie, true);

            $this->addFlash('success', 'Movie created successfully');
            return $this->redirectToRoute('app_movie_list');
        }

        return $this->render('movie/create.html.twig', [
            'creation_form' => $form->createView(),
        ]);
    }
}
