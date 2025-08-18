<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Veterinarian;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $veterinarian = new Veterinarian();
        $veterinarian->setName('Martin Jacquin');
        $veterinarian->setCrmv('CRMV-MG-1234');
        $manager->persist($veterinarian);

        $manager->flush();
    }
}
