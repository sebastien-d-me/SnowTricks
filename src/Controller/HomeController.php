<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController {
    #[Route("/")]
    public function index(TrickRepository $trickRepository): Response {
        $tricks = $trickRepository->findAll();

        return $this->render("pages/home.html.twig", [
            "tricks" => $tricks,
        ]);
    }
}