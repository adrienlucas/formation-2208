<?php
declare(strict_types=1);

namespace App\Gateway;

use App\Entity\Movie;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Contracts\Cache\CacheInterface;

#[AsDecorator(OmdbGateway::class)]
class CacheableOmdbGateway extends OmdbGateway
{
    public function __construct(
        private OmdbGateway $omdbGateway,
        private CacheInterface $cache,
    ) {}

    public function getPosterByMovie(Movie $movie): string
    {
        $cacheKey = 'movie_poster_'.md5($movie->getTitle());

        return $this->cache->get($cacheKey, function() use ($movie) {
            return $this->omdbGateway->getPosterByMovie($movie);
        });
    }

    public function getDescriptionByMovie(Movie $movie): string
    {
        return $this->omdbGateway->getDescriptionByMovie($movie); // TODO: Change the autogenerated stub
    }
}