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
use App\Repository\TrickGroupRepository;


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
            $currentDate = date("Y-m-d H:i:s");

            $trick = new Trick();
            $trick->setName($trickName);
            $trick->setDescription($form->get("description")->getData());
            $trick->setIdTrickGroup($form->get("idTrickGroup")->getData());
            $trick->setSlug($trickSlug);
            $trick->setCreatedAt(\DateTime::createFromFormat("Y-m-d H:i:s", $currentDate));
            $trick->setupdatedAt(\DateTime::createFromFormat("Y-m-d H:i:s", $currentDate));

            $entityManager->persist($trick);
            $entityManager->flush();

            $trickId = $trickRepository->findOneBy(["id" => $trick->getId()]);
            
            // Sauvegarde de l'image à la une
            $featuredFile = $form->get("featured")->getData();

            if($featuredFile) {
                $featuredFileName = $slugger->slug(pathinfo($featuredFile->getClientOriginalName(), PATHINFO_FILENAME))."-".uniqid().".".$featuredFile->guessExtension();
                $featuredFile->move($this->getParameter("featured_directory"), $featuredFileName);
                $featuredPath = "assets/images/tricks/featured/".$featuredFileName;
            } else {
                $featuredPath = "assets/images/tricks/placeholder/trick_placeholder.webp";
            }

            $media = new Media();
            $media->setIdTrick($trickId);
            $media->setType("image");
            $media->setPath($featuredPath);
            $media->setFeatured(true);

            $entityManager->persist($media);
            $entityManager->flush();

            // Sauvegarde des autres médias
            $mediasFile = $form->get("medias")->getData();
            foreach($mediasFile as $mediaFile) {
                $mediaExtension = $mediaFile->guessExtension();
                $media = new Media();

                if($mediaExtension === "mp4") {
                    $mediaRepository = "media_video_directory";
                    $mediaPath = "assets/videos/tricks/";
                    $media->setType("video");
                } else {
                    $mediaRepository = "media_image_directory";
                    $mediaPath = "assets/images/tricks/";
                    $media->setType("image");
                }

                $mediaFileName = $slugger->slug(pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME))."-".uniqid().".".$mediaExtension;
                
                $mediaFile->move($this->getParameter($mediaRepository), $mediaFileName);
                $trickId = $trickRepository->findOneBy(["id" => $trick->getId()]);

                
                $media->setIdTrick($trickId);
                $media->setPath($mediaPath.$mediaFileName);
                $media->setFeatured(false);

                $entityManager->persist($media);
                $entityManager->flush();
            }

            // Enregistre les embed
            $mediasEmbed = $form->get("mediasEmbed")->getData();
            
            if($mediasEmbed) {
                $mediasEmbedList = explode("\n", $mediasEmbed);
                foreach($mediasEmbedList as $mediaEmbedItem) {
                    if(parse_url($mediaEmbedItem)["host"] === "www.youtube.com") {   
                        $embedVideo = str_replace("v=", "", parse_url($mediaEmbedItem)["query"]);
                        $embedUrl = "https://youtube.com/embed/".$embedVideo;
                    } else if(parse_url($mediaEmbedItem)["host"] === "www.dailymotion.com") {
                        $embedVideo = str_replace("/video/", "",  parse_url($mediaEmbedItem)["path"]);
                        $embedUrl = "https://dailymotion.com/embed/video/".$embedVideo;
                    } else {
                        $this->addFlash("warning", "Veuillez ne prendre un lien que de Youtube et de Dailymotion.");
                        return $this->redirectToRoute("trick_create");
                    }

                    $media = new Media();
                    $media->setIdTrick($trickId);
                    $media->setType("embed");
                    $media->setSrc($embedUrl);
                    $media->setFeatured(false);

                    $entityManager->persist($media);
                    $entityManager->flush();
                }
            }

            $this->addFlash("success", "Votre trick a été ajouté !");

            return $this->redirectToRoute("home", ["_fragment" => "home__messages"]);
        }

        return $this->render("pages/tricks/create.html.twig", [
            "form" => $form->createView()
        ]);
    }


    #[Route(name: "trick_presentation", path: "/trick/{trickSlug}")]
    public function view(TrickRepository $trickRepository, string $trickSlug, MediaRepository $mediaRepository): Response {        
        $trick = $trickRepository->findOneBy(["slug" => $trickSlug]);
        $medias = $mediaRepository->findBy(["idTrick" => $trick->getId()]);

        $data = [
            "trick" => $trick,
            "medias" => $medias
        ];

        return $this->render("pages/tricks/presentation.html.twig", [
            "data" => $data
        ]);
    }


    #[Route(name: "trick_edit", path: "/trick/edit/{trickSlug}")]
    public function edit(Request $request, TrickRepository $trickRepository, string $trickSlug, MediaRepository $mediaRepository, TrickGroupRepository $trickGroupRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute("home");
        }

        $trick = $trickRepository->findOneBy(["slug" => $trickSlug]);
        $medias = $mediaRepository->findBy(["idTrick" => $trick->getId()]);
        $trickGroups = $trickGroupRepository->findAll();

        $category = $trickGroupRepository->findOneBy(["id" => $trick->getIdTrickGroup()]);
        $featured = $mediaRepository->findOneBy(["idTrick" => $trick->getId(), "featured" => true]);
        $embed = $mediaRepository->findBy(["idTrick" => $trick->getId(), "type" => "embed"]);

        if ($request->isMethod("POST")) {
            $featuredForm = $request->files->get("featured_media");
            $nameForm = $request->get("name");
            $slugForm = strtolower(str_replace(" ", "-", $nameForm));
            $descriptionForm = $request->get("description");
            $groupeForm = $request->get("groupe");
            $embedForm = $request->get("embed");
            $mediasForm = $request->files->get("medias");

            $checkExist = $trickRepository->findOneBy(["name" => $nameForm]);

            if($checkExist && $checkExist->getId() !== $trick->getId()) {
                $this->addFlash("warning", "Un trick du même nom existe déjà.");
                return $this->redirectToRoute("trick_edit", ["trickSlug" => $trick->getSlug()]);
            }
            
            if($featuredForm !== null) {
                $featuredFileName = $slugger->slug(pathinfo($featuredForm->getClientOriginalName(), PATHINFO_FILENAME))."-".uniqid().".".$featuredForm->guessExtension();
                $featuredForm->move($this->getParameter("featured_directory"), $featuredFileName);
                $featuredPath = "assets/images/tricks/featured/".$featuredFileName;
                $featured->setPath($featuredPath);
            }

            $selectedGroup = $trickGroupRepository->findOneBy(["id" => $groupeForm]);
            
            $trick->setName($nameForm);
            $trick->setDescription($descriptionForm);
            $trick->setIdTrickGroup($selectedGroup);
            $trick->setSlug($slugForm);            
            $trick->setUpdatedAt(\DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s")));
        
            $trickId = $trickRepository->findOneBy(["id" => $trick->getId()]);

            // Sauvegarde des autres médias
            if($mediasForm) {
                foreach($mediasForm as $mediaFile) {
                    $mediaExtension = $mediaFile->guessExtension();
                    $media = new Media();

                    if($mediaExtension === "mp4") {
                        $mediaRepository = "media_video_directory";
                        $mediaPath = "assets/videos/tricks/";
                        $media->setType("video");
                    } else {
                        $mediaRepository = "media_image_directory";
                        $mediaPath = "assets/images/tricks/";
                        $media->setType("image");
                    }

                    $mediaFileName = $slugger->slug(pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME))."-".uniqid().".".$mediaExtension;
                    
                    $mediaFile->move($this->getParameter($mediaRepository), $mediaFileName);
                    
                    $media->setIdTrick($trickId);
                    $media->setPath($mediaPath.$mediaFileName);
                    $media->setFeatured(false);
                    $entityManager->persist($media);
                }
            }

            // Enregistre les embed
            if($embedForm) {
                $mediasEmbedList = explode("\n", $embedForm);
                foreach($mediasEmbedList as $mediaEmbedItem) {
                    if(parse_url($mediaEmbedItem)["host"] === "www.youtube.com") {   
                        $embedVideo = rtrim(str_replace("v=", "", parse_url($mediaEmbedItem)["query"]), "_");
                        $embedUrl = "https://youtube.com/embed/".$embedVideo;
                    } else if(parse_url($mediaEmbedItem)["host"] === "www.dailymotion.com") {
                        $embedVideo = str_replace("/video/", "",  parse_url($mediaEmbedItem)["path"]);
                        $embedUrl = "https://dailymotion.com/embed/video/".$embedVideo;
                    } else {
                        $this->addFlash("warning", "Veuillez ne prendre un lien que de Youtube et de Dailymotion.");
                        return $this->redirectToRoute("trick_edit", ["trickSlug" => $trick->getSlug()]);
                    }

                    $media = new Media();
                    $media->setIdTrick($trickId);
                    $media->setType("embed");
                    $media->setSrc($embedUrl);
                    $media->setFeatured(false);
                    $entityManager->persist($media);
                }
            }

            $entityManager->flush();

            $this->addFlash("success", "Le trick a été modifié !");
            return $this->redirectToRoute("trick_presentation", ["trickSlug" => $trick->getSlug()]);
        }    
        
        $data = [
            "name" => $trick->getName(),
            "description" => $trick->getDescription(),
            "category" => $category->getId(),
            "medias" => $medias,
            "featured" => $featured->getPath(),
            "featuredId" => $featured->getId(),
            "slug" => $trick->getSlug()
        ];

        return $this->render("pages/tricks/edit.html.twig", [
            "data" => $data,
            "trickGroups" => $trickGroups
        ]);
    }


    #[Route(name: "trick_delete", path: "/trick/delete/{trickSlug}")]
    public function delete(TrickRepository $trickRepository, string $trickSlug, MediaRepository $mediaRepository, EntityManagerInterface $entityManager): Response {        
        if (!$this->getUser()) {
            return $this->redirectToRoute("home");
        }

        $trick = $trickRepository->findOneBy(["slug" => $trickSlug]);

        if($trick) {
            $medias = $mediaRepository->findBy(["idTrick" => $trick->getId()]);

            foreach($medias as $media) {
                $mediaPath = $media->getPath();
                unlink($mediaPath);
                $entityManager->remove($media);
            }

            $entityManager->remove($trick);
            $entityManager->flush();

            $this->addFlash("success", "Le trick a été supprimé !");
            return $this->redirectToRoute("home", ["_fragment" => "home__messages"]);
        } else {
            $this->addFlash("warning", "Le trick n'existe pas.");
            return $this->redirectToRoute("home", ["_fragment" => "home__messages"]);
        }
    }


    #[Route(name: "featured_delete", path: "/media/delete/featured/{mediaId}")]
    public function featuredDelete(MediaRepository $mediaRepository, string $mediaId, TrickRepository $trickRepository, EntityManagerInterface $entityManager): Response {        
        if (!$this->getUser()) {
            return $this->redirectToRoute("home");
        }

        $media = $mediaRepository->findOneBy(["id" => $mediaId]);
        $trick = $trickRepository->findOneBy(["id" => $media->getIdTrick()]);
        $trick->setUpdatedAt(\DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s")));

        if($media) {
            $mediaPath = $media->getPath();
            unlink($mediaPath);
            $media->setPath("assets/images/tricks/placeholder/trick_placeholder.webp");
            $entityManager->flush();

            $this->addFlash("success", "L'image à la une a été supprimé !");
            return $this->redirectToRoute("trick_presentation", ["trickSlug" => $trick->getSlug()]);
        } else {
            $this->addFlash("warning", "L'image à la une n'existe pas.");
            return $this->redirectToRoute("trick_presentation", ["trickSlug" => $trick->getSlug()]);
        }
    }


    #[Route(name: "media_delete", path: "/media/delete/{mediaId}")]
    public function mediaDelete(MediaRepository $mediaRepository, string $mediaId, TrickRepository $trickRepository, EntityManagerInterface $entityManager): Response {        
        if (!$this->getUser()) {
            return $this->redirectToRoute("home");
        }

        $media = $mediaRepository->findOneBy(["id" => $mediaId]);
        $trick = $trickRepository->findOneBy(["id" => $media->getIdTrick()]);
        $trick->setUpdatedAt(\DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s")));

        if($media) {
            $mediaPath = $media->getPath();
            unlink($mediaPath);
            $entityManager->remove($media);
            $entityManager->flush();

            $this->addFlash("success", "Le média a été supprimé !");
            return $this->redirectToRoute("trick_presentation", ["trickSlug" => $trick->getSlug()]);
        } else {
            $this->addFlash("warning", "Le média n'existe pas.");
            return $this->redirectToRoute("trick_presentation", ["trickSlug" => $trick->getSlug()]);
        }
    }
}
