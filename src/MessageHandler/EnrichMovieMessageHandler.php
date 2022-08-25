<?php

namespace App\MessageHandler;

use App\Entity\Movie;
use App\Gateway\OmdbGateway;
use App\Message\EnrichMovieMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class EnrichMovieMessageHandler implements MessageHandlerInterface
{
    public function __construct(
        private OmdbGateway $omdbGateway,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function __invoke(EnrichMovieMessage $message)
    {
        $movie = $this->entityManager->find(Movie::class, $message->movie->getId());
        $movieDescription = $this->omdbGateway->getDescriptionByMovie($message->movie);
        $movie->setDescription($movieDescription);

        $this->entityManager->flush();
    }
}
