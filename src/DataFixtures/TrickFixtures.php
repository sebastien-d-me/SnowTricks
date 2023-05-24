<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\TrickGroupFixtures;
use Doctrine\Persistence\ObjectManager;
use App\Entity\TrickGroup;
use App\Entity\Trick;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            TrickGroupFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $tricksData = [
            [
                "name" => "tail press",
                "description" => "",
                "idTrickGroup" => "butter"
            ],
            [
                "name" => "nose press",
                "description" => "",
                "idTrickGroup" => "butter"
            ],
            [
                "name" => "tripod",
                "description" => "",
                "idTrickGroup" => "butter"
            ],
            [
                "name" => "tail-drag 360",
                "description" => "",
                "idTrickGroup" => "butter"
            ],
            [
                "name" => "indy",
                "description" => "",
                "idTrickGroup" => "grabs"
            ],
            [
                "name" => "stalefish",
                "description" => "",
                "idTrickGroup" => "grabs"
            ],
            [
                "name" => "tail",
                "description" => "",
                "idTrickGroup" => "grabs"
            ],
            [
                "name" => "method",
                "description" => "",
                "idTrickGroup" => "grabs"
            ],
            [
                "name" => "wildcat",
                "description" => "",
                "idTrickGroup" => "spins, flips and corks"
            ],
            [
                "name" => "tamedog",
                "description" => "",
                "idTrickGroup" => "spins, flips and corks"
            ],
            [
                "name" => "frontflip",
                "description" => "",
                "idTrickGroup" => "spins, flips and corks"
            ],
            [
                "name" => "corked spin",
                "description" => "",
                "idTrickGroup" => "spins, flips and corks"
            ],
            [
                "name" => "frontside boardslide",
                "description" => "",
                "idTrickGroup" => "rails and boxes"
            ],
            [
                "name" => "backside boardslide",
                "description" => "",
                "idTrickGroup" => "rails and boxes"
            ],
            [
                "name" => "50-50",
                "description" => "",
                "idTrickGroup" => "rails and boxes"
            ],
            [
                "name" => "bluntslide",
                "description" => "",
                "idTrickGroup" => "rails and boxes"
            ],
        ];

        foreach($tricksData as $trickData) {
            $trickGroup = $manager->getRepository(TrickGroup::class)->findOneBy(["name" => $trickData["idTrickGroup"]]);

            $trick = new Trick();
            $trick->setName($trickData["name"]);
            $trick->setDescription($trickData["description"]);
            $trick->setIdTrickGroup($trickGroup);
            $manager->persist($trick);
        }

        $manager->flush();
    }
}