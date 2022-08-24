<?php
declare(strict_types=1);

namespace App\Gateway;

use App\Entity\Movie;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbGateway
{
    public function __construct(
        private HttpClientInterface $httpClient,
    )
    {
    }

    public function getPosterByMovie(Movie $movie): string
    {
        $apiUrl = sprintf(
            'https://www.omdbapi.com/?apikey=e0ded5e2&t=%s',
            $movie->getTitle()
        );
        try {
            $response = $this->httpClient->request('GET', $apiUrl);
            $movieProperties = $response->toArray();

            if(!isset($movieProperties['Poster'])) {
                throw new \Exception('Poster not found in movie properties.');
            }

            $moviePoster = $movieProperties['Poster'];
        } catch (\Throwable $e) {
            $moviePoster = '';
        }

        return $moviePoster;
    }

    public function getDescriptionByMovie(Movie $movie): string
    {

        $apiUrl = sprintf(
            'https://www.omdbapi.com/?apikey=e0ded5e2&t=%s',
            $movie->getTitle()
        );
        try {
            $response = $this->httpClient->request('GET', $apiUrl);
            $movieProperties = $response->toArray();

            if(!isset($movieProperties['Plot'])) {
                throw new \Exception('Description not found in movie properties.');
            }

            $movieDescription = $movieProperties['Plot'];
        } catch (\Throwable $e) {
            $movieDescription = '';
        }

        return $movieDescription;
    }
}