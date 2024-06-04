<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $movie = new Movie();
        $movie->setTitle('The Symfony Lecture');
        $movie->setReleaseYear(2022);
        $movie->setDescription('Our symfony project demo ');
        $movie->setImagePath('https://cdn.pixabay.com/photo/2023/01/13/14/58/snake-7716269_1280.jpg');

        // add data to  pivot table 
        $movie->addActor($this->getReference('actor_1'));
        $movie->addActor($this->getReference('actor_2'));

        $manager->persist($movie);

        $movie2 = new Movie();
        $movie2->setTitle('The Twig Lecture ');
        $movie2->setReleaseYear(2022);
        $movie2->setDescription('Our symfony/Twig project demo ');
        $movie2->setImagePath('https://cdn.pixabay.com/photo/2024/05/05/05/55/goose-8740266_1280.jpg');

        // add data to  pivot table 
        $movie->addActor($this->getReference('actor_3'));

        $manager->persist($movie2);

        $manager->flush();
    }
}
