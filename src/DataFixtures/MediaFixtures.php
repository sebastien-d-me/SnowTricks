<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\TrickFixtures;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Trick;
use App\Entity\Media;

class MediaFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            TrickFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $mediasPath = [
            [
                "name" => "Tail Press",
                "extension" => "webp",
            ],
            [
                "name" => "Nose Press",
                "extension" => "webp",
            ],
            [
                "name" => "Tripod",
                "extension" => "webp",
            ],
            [
                "name" => "Tail-Drag 360",
                "extension" => "webp",
            ],
            [
                "name" => "Indy",
                "extension" => "webp",
            ],
            [
                "name" => "Stalefish",
                "extension" => "webp",
            ],
            [
                "name" => "Tail",
                "extension" => "webp",
            ],
            [
                "name" => "Method",
                "extension" => "webp",
            ],
            [
                "name" => "Wildcat",
                "extension" => "webp",
            ],
            [
                "name" => "Tamedog",
                "extension" => "webp",
            ],
            [
                "name" => "Frontflip",
                "extension" => "webp",
            ],
            [
                "name" => "Corked Spin",
                "extension" => "webp",
            ],
            [
                "name" => "Frontside Boardslide",
                "extension" => "webp",
            ],
            [
                "name" => "Backside Boardslide",
                "extension" => "webp",
            ],
            [
                "name" => "50-50",
                "extension" => "webp",
            ],
            [
                "name" => "Bluntslide",
                "extension" => "webp",
            ],
        ];

        foreach($mediasPath as $mediaPath) {
            $trick = $manager->getRepository(Trick::class)->findOneBy(["name" => $mediaPath["name"]]);

            $mediaName = strtolower(str_replace(" ", "-", $mediaPath["name"]));
            $mediaFileName = $mediaName."-".uniqid().".".$mediaPath["extension"];

            copy("public/assets/images/tricks/placeholder/".$mediaPath["name"].".".$mediaPath["extension"], "public/assets/images/tricks/featured/".$mediaFileName);
            
            $media = new Media();
            $media->setPath("images/tricks/featured/".$mediaFileName);
            $media->setType("image");
            $media->setIdTrick($trick);
            $media->setFeatured(true);
            $manager->persist($media);
        }

        $manager->flush();
    }
}
