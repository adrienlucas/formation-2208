<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [GenreFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $movieTitles = [
            'The Godfather',
            'The Matrix',
            'The Lord of the Rings: The Return of the King',
            'Avatar',
            'Pulp Fiction',
            'The Dark Knight',
            'Interstellar',
            'Fight Club',
            'Star Wars',
            'eoijfoerijfeorijeorijgfoeirjfojier',
        ];

        /** @var Genre $genreAction */
        $genreAction = $this->getReference(GenreFixtures::GENRE_ACTION);

        foreach($movieTitles as $title) {
            $movie = new Movie();
            $movie->setTitle($title);
            $movie->addGenre($genreAction);
            $manager->persist($movie);
        }

        $movie = new Movie();
        $movie->setTitle('Parasite');
        $movie->setDescription('Someone is in my basement !');
        $movie->addGenre($this->getReference(GenreFixtures::GENRE_COMEDY));
        $manager->persist($movie);

        $manager->flush();
    }
}
