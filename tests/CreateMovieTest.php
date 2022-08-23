<?php

namespace App\Tests;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateMovieTest extends WebTestCase
{
    public function testAddingANewMovieToDatabase(): void
    {
        $client = static::createClient();
        $client->request('GET', '/movie/create');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('input#movie_title');
        $this->assertSelectorExists('textarea#movie_description');
        $this->assertSelectorExists('div#movie_genres');

        $client->submitForm('Create', [
            'movie[title]' => 'The Matrix Revolution',
            'movie[description]' => 'Neo goes brruuuu !',
            'movie[genres]' => ['1'],
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', 'Movie created successfully');
        $movieRepository = $this->getContainer()->get(MovieRepository::class);

        $this->assertNotNull($movie = $movieRepository->findOneBy(['title' => 'The Matrix Revolution']));
        $movieRepository->remove($movie);
    }
}
