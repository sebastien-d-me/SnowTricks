<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Form\TrickFormType;


class TrickController extends AbstractController {
    #[Route("/trick/ajouter", name: "trick_create")]
    public function create(Request $request, EntityManagerInterface $entityManager): Response {
        $form = $this->createForm(TrickFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        }

        return $this->render("pages/tricks/create.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
