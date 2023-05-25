<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrickRepository;
use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends AbstractController {
    #[Route("/", name: "home")]
    public function index(TrickRepository $trickRepository, MediaRepository $mediaRepository): Response {
        $tricks = $trickRepository->findAll();
        $tricksData = [];

        foreach ($tricks as $trick) {
            $trickCover = $mediaRepository->findOneBy(["idTrick" => $trick->getId()]);
            $tricksData[$trick->getId()] = [
                "trick" => $trick,
                "cover" => $trickCover,
            ];
        }


        return $this->render("pages/home.html.twig", [
            "tricksData" => $tricksData,
        ]);
    }
}