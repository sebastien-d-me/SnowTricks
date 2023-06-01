<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\LoginCredentials;
use App\Form\RegistrationFormType;
use App\Entity\Hash;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\HashRepository;
use App\Repository\LoginCredentialsRepository;


class RegistrationController extends AbstractController {
    #[Route("/inscription", name: "register")]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($userPasswordHasher->hashPassword(
                $user, $form->get("password")->getData()
            ));
            $user->setIsActive(false);
            $entityManager->persist($user);

            $token = bin2hex(random_bytes(64));
            $hash = new Hash();
            $hash->setIdLoginCredentials($user);
            $hash->setHash($token);
            $hash->setIsActive(true);
            $entityManager->persist($hash);

            $entityManager->flush();

            $email = new Email();
            $email->from("noreply@snowtricks.com");
            $email->to($form->get("email")->getData());
            $email->subject("Activation de votre compte SnowTricks");
            $email->html("<a href='".$this->generateUrl("register_activation", ["token" => $token], UrlGeneratorInterface::ABSOLUTE_URL)."'>Cliquez ici pour activer votre compte !</a>");
            $mailer->send($email);

            $this->addFlash("success", "Votre compte a été crée, merci de l'activer !");

            return $this->redirectToRoute("home", ["_fragment" => "home__messages"]);
        }

        return $this->render("pages/members/register.html.twig", [
            "form" => $form->createView(),
        ]);
    }


    #[Route("/activation/{token}", name: "register_activation")]
    public function activate(HashRepository $hashRepository, LoginCredentialsRepository $loginCredentialsRepository, EntityManagerInterface $entityManager, string $token): Response {
        $hash = $hashRepository->findOneBy(["hash" => $token]);

        if($hash && $hash->isIsActive() === true) {
            $user = $loginCredentialsRepository->findOneBy(["id" => $hash->getIdLoginCredentials()]);
            $user->setIsActive(true);
            
            $hash->setIsActive(false);

            $entityManager->persist($user);
            $entityManager->persist($hash);
            $entityManager->flush();       

            $this->addFlash("success", "Votre compte est activé !");

        } else {
            $this->addFlash("warning", "Une erreur est arrivée lors de l'activation de votre compte.");
        }

        return $this->redirectToRoute("home", ["_fragment" => "home__messages"]);
    }
}