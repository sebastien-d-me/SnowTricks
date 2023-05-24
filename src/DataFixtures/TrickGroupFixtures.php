<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\TrickGroup;

class TrickGroupFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $trickGroupsName = [
            "butter",
            "grabs",
            "spins, flips and corks",
            "rails and boxes"
        ];

        foreach($trickGroupsName as $trickGroupName) {
            $trickGroup = new TrickGroup();
            $trickGroup->setName($trickGroupName);
            $manager->persist($trickGroup);
        }

        $manager->flush();
    }
}
