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
                "name" => "Tail Press",
                "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                "idTrickGroup" => "butter"
            ],
            [
                "name" => "Nose Press",
                "description" => "Duis sit amet lacus in libero posuere bibendum.",
                "idTrickGroup" => "butter"
            ],
            [
                "name" => "Tripod",
                "description" => "Nulla vel lorem eu tortor blandit dapibus.",
                "idTrickGroup" => "butter"
            ],
            [
                "name" => "Tail-Drag 360",
                "description" => "Mauris sit amet nisl eget nulla placerat blandit vitae et dui.",
                "idTrickGroup" => "butter"
            ],
            [
                "name" => "Indy",
                "description" => "Donec venenatis est sed porttitor commodo.",
                "idTrickGroup" => "grabs"
            ],
            [
                "name" => "Stalefish",
                "description" => "Quisque pulvinar purus congue, faucibus nunc sit amet, tincidunt purus.",
                "idTrickGroup" => "grabs"
            ],
            [
                "name" => "Tail",
                "description" => "Donec porttitor lacus nec dapibus auctor.",
                "idTrickGroup" => "grabs"
            ],
            [
                "name" => "Method",
                "description" => "Phasellus vel enim eu nibh vestibulum sodales.",
                "idTrickGroup" => "grabs"
            ],
            [
                "name" => "Wildcat",
                "description" => "Nunc sed neque euismod, tincidunt est nec, molestie dolor.",
                "idTrickGroup" => "spins, flips and corks"
            ],
            [
                "name" => "Tamedog",
                "description" => "Nullam quis libero vehicula, hendrerit nulla ut, dictum dolor.",
                "idTrickGroup" => "spins, flips and corks"
            ],
            [
                "name" => "Frontflip",
                "description" => "Proin ut urna non ante ornare tristique.",
                "idTrickGroup" => "spins, flips and corks"
            ],
            [
                "name" => "Corked Spin",
                "description" => "Proin porttitor lacus at erat ultrices ornare.",
                "idTrickGroup" => "spins, flips and corks"
            ],
            [
                "name" => "Frontside Boardslide",
                "description" => "Proin varius mi sed ante pretium volutpat.",
                "idTrickGroup" => "rails and boxes"
            ],
            [
                "name" => "Backside Boardslide",
                "description" => "Maecenas elementum quam ut dolor pharetra mollis.",
                "idTrickGroup" => "rails and boxes"
            ],
            [
                "name" => "50-50",
                "description" => "Duis quis quam non lacus ornare ultrices.",
                "idTrickGroup" => "rails and boxes"
            ],
            [
                "name" => "Bluntslide",
                "description" => "Etiam faucibus sapien vitae lobortis aliquam.",
                "idTrickGroup" => "rails and boxes"
            ],
        ];

        foreach($tricksData as $trickData) {
            $trickGroup = $manager->getRepository(TrickGroup::class)->findOneBy(["name" => $trickData["idTrickGroup"]]);
            $trickSlug = strtolower(str_replace(" ", "-", $trickData["name"]));
            $currentDate = date("Y-m-d H:i:s");

            $trick = new Trick();
            $trick->setName($trickData["name"]);
            $trick->setDescription($trickData["description"]);
            $trick->setIdTrickGroup($trickGroup);
            $trick->setSlug($trickSlug);
            $trick->setCreatedAt(\DateTime::createFromFormat("Y-m-d H:i:s", $currentDate));
            $trick->setupdatedAt(\DateTime::createFromFormat("Y-m-d H:i:s", $currentDate));
            $manager->persist($trick);
        }

        $manager->flush();
    }
}