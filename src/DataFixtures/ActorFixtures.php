<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $actor = new Actor();
        $actor -> setName('Rahul_Smpfony1');
        $manager->persist($actor);

        $actor1 = new Actor();
        $actor1 -> setName('Shreya_Smpfony1');
        $manager->persist($actor1);

        $actor2 = new Actor();
        $actor2 -> setName('Jyoti_Smpfony1');
        $manager->persist($actor2);
;

        $manager->flush();
        $this->addReference('actor_1',$actor);
        $this->addReference('actor_2',$actor1);
        $this->addReference('actor_3',$actor2);
    }
}
