<?php

namespace App\DataFixtures;

use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServiceFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $services = array('Grooming', 'Veterinary care', 'Training', 'Pet insurance', 'Psychologist');

        foreach ($services as $service) {
            $serviceEntity = new Service();
            $serviceEntity->setType($service);
            $serviceEntity->setPrice(rand(50, 10000));
            $manager->persist($serviceEntity);
        }

        $manager->flush();
    }
}
