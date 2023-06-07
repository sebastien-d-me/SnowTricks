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
                "name" => "tail press",
                "extension" => "webp",
            ],
            [
                "name" => "nose press",
                "extension" => "webp",
            ],
            [
                "name" => "tripod",
                "extension" => "webp",
            ],
            [
                "name" => "tail-drag 360",
                "extension" => "webp",
            ],
            [
                "name" => "indy",
                "extension" => "webp",
            ],
            [
                "name" => "stalefish",
                "extension" => "webp",
            ],
            [
                "name" => "tail",
                "extension" => "webp",
            ],
            [
                "name" => "method",
                "extension" => "webp",
            ],
            [
                "name" => "wildcat",
                "extension" => "webp",
            ],
            [
                "name" => "tamedog",
                "extension" => "webp",
            ],
            [
                "name" => "frontflip",
                "extension" => "webp",
            ],
            [
                "name" => "corked spin",
                "extension" => "webp",
            ],
            [
                "name" => "frontside boardslide",
                "extension" => "webp",
            ],
            [
                "name" => "backside boardslide",
                "extension" => "webp",
            ],
            [
                "name" => "50-50",
                "extension" => "webp",
            ],
            [
                "name" => "bluntslide",
                "extension" => "webp",
            ],
        ];

        foreach($mediasPath as $mediaPath) {
            $trick = $manager->getRepository(Trick::class)->findOneBy(["name" => $mediaPath["name"]]);

            $media = new Media();
            $media->setPath("images/tricks/placeholder/".$mediaPath["name"].".".$mediaPath["extension"]);
            $media->setType("image");
            $media->setIdTrick($trick);
            $media->setFeatured(true);
            $manager->persist($media);
        }

        $manager->flush();
    }
}
