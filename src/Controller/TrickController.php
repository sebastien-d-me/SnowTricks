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
use App\Repository\MediaRepository;


class TrickController extends AbstractController {
    #[Route(name: "trick_create", path: "/trick/ajouter")]
    public function create(Request $request, TrickRepository $trickRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute("home");
        }
        
        $form = $this->createForm(TrickFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {           
            $trickName = $form->get("name")->getData();
            $checkExist = $trickRepository->findOneBy(["name" => $trickName]);

            if($checkExist) {
                $this->addFlash("warning", "Un trick du même nom existe déjà.");
                return $this->redirectToRoute("trick_create");
            }

            // Creation et enregistrement du trick
            $trickSlug = strtolower(str_replace(" ", "-", $trickName));

            $trick = new Trick();
            $trick->setName($trickName);
            $trick->setDescription($form->get("description")->getData());
            $trick->setIdTrickGroup($form->get("idTrickGroup")->getData());
            $trick->setSlug($trickSlug);

            $entityManager->persist($trick);
            $entityManager->flush();

            $trickId = $trickRepository->findOneBy(["id" => $trick->getId()]);
            
            // Sauvegarde de l'image à la une
            $featuredFile = $form->get("featured")->getData();
            $featuredFileName = $slugger->slug(pathinfo($featuredFile->getClientOriginalName(), PATHINFO_FILENAME))."-".uniqid().".".$featuredFile->guessExtension();
            $featuredFile->move($this->getParameter("featured_directory"), $featuredFileName);
            
            $media = new Media();
            $media->setIdTrick($trickId);
            $media->setType("image");
            $media->setPath("assets/images/tricks/featured/".$featuredFileName);
            $media->setFeatured(true);

            $entityManager->persist($media);
            $entityManager->flush();

            // Sauvegarde des autres médias
            $mediasFile = $form->get("medias")->getData();
            foreach($mediasFile as $mediaFile) {
                $mediaExtension = $mediaFile->guessExtension();

                if($mediaExtension === "mp4") {
                    $mediaRepository = "media_video_directory";
                    $mediaPath = "assets/videos/tricks/";
                } else {
                    $mediaRepository = "media_image_directory";
                    $mediaPath = "assets/images/tricks/";
                }

                $mediaFileName = $slugger->slug(pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME))."-".uniqid().".".$mediaExtension;
                
                $mediaFile->move($this->getParameter($mediaRepository), $mediaFileName);
                $trickId = $trickRepository->findOneBy(["id" => $trick->getId()]);

                $media = new Media();
                $media->setIdTrick($trickId);
                $media->setType("image");
                $media->setPath($mediaPath.$mediaFileName);
                $media->setFeatured(false);

                $entityManager->persist($media);
                $entityManager->flush();
            }

            // Enregistre les embed
            $mediasEmbed = $form->get("mediasEmbed")->getData();
            $mediasEmbedList = explode("\n", $mediasEmbed);
            foreach($mediasEmbedList as $mediaEmbedItem) {
                $media = new Media();
                $media->setIdTrick($trickId);
                $media->setType("embed");
                $media->setSrc($mediaEmbedItem);
                $media->setFeatured(false);

                $entityManager->persist($media);
                $entityManager->flush();
            }

            $this->addFlash("success", "Votre trick a été ajouté !");

            return $this->redirectToRoute("home", ["_fragment" => "home__messages"]);
        }

        return $this->render("pages/tricks/create.html.twig", [
            "form" => $form->createView()
        ]);
    }


    #[Route(name: "trick_presentation", path: "/trick/{trickSlug}")]
    public function view(TrickRepository $trickRepository, string $trickSlug ,MediaRepository $mediaRepository): Response {        
        $trick = $trickRepository->findOneBy(["slug" => $trickSlug]);
        $medias = $mediaRepository->findBy(["idTrick" => $trick->getId()]);

        $data = [
            "trick" => array($trick),
            "medias" => $medias
        ];

        return $this->render("pages/tricks/presentation.html.twig", [
            "data" => $data
        ]);
    }
}
