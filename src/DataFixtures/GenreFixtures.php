<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GenreFixtures extends Fixture
{
    public const GENRE_ACTION = 'action';
    public const GENRE_COMEDY = 'comedy';

    public function load(ObjectManager $manager): void
    {
        $genre = new Genre();
        $genre->setName('Action');
        $this->addReference(self::GENRE_ACTION, $genre);
        $manager->persist($genre);

        $genre = new Genre();
        $genre->setName('Science-Fiction');
        $manager->persist($genre);

        $genre = new Genre();
        $genre->setName('Comedy');
        $this->addReference(self::GENRE_COMEDY, $genre);
        $manager->persist($genre);

        $manager->flush();
    }
}
