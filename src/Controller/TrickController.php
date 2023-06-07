<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Form\TrickFormType;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\TrickRepository;
use App\Entity\Trick;
use App\Entity\Media;


class TrickController extends AbstractController {
    #[Route("/trick/ajouter", name: "trick_create")]
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager, TrickRepository $trickRepository): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute("home");
        }
        
        $form = $this->createForm(TrickFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Creation et enregistrement du trick
            $trickSlug = strtolower(str_replace(" ", "-", $form->get("name")->getData()));

            $trick = new Trick();
            $trick->setName($form->get("name")->getData());
            $trick->setDescription($form->get("description")->getData());
            $trick->setIdTrickGroup($form->get("idTrickGroup")->getData());
            $trick->setSlug($trickSlug);

            $entityManager->persist($trick);
            
            // Sauvegarde de l'image à la une
            $featuredFile = $form->get("featured")->getData();
            $featuredFileName = $slugger->slug(pathinfo($featuredFile->getClientOriginalName(), PATHINFO_FILENAME))."-".uniqid().".".$featuredFile->guessExtension();
            $featuredFile->move($this->getParameter("featured_directory"), $featuredFileName);
            $trickId = $trickRepository->findOneBy(["id" => $trick->getId()]);

            $media = new Media();
            $media->setIdTrick($trickId);
            $media->setType("image");
            $media->setPath($featuredFileName);
            $media->setFeatured(true);
            $entityManager->persist($media);
            $entityManager->flush();

            // Sauvegarde des autres médias
        }

        return $this->render("pages/tricks/create.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
