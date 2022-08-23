<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    #[Route('/hello/{name}', name: 'app_helloworld')]
    public function __invoke(Request $request, string $name = null): Response
    {
        if($name === null) {
            $name = $request->query->get('name', 'World');
        }

        return $this->render('homepage.html.twig', ['name' => $name]);
    }
}
