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
    #[Route(name: "register", path: "/inscription")]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute("home");
        }
        
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($userPasswordHasher->hashPassword(
                $user, $form->get("password")->getData()
            ));
            $user->setIsActive(false);

            $token = bin2hex(random_bytes(64));
            $hash = new Hash();
            $hash->setIdLoginCredentials($user);
            $hash->setHash($token);
            $hash->setIsActive(true);

            $entityManager->persist($user);
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
            "form" => $form->createView()
        ]);
    }


    #[Route(name: "register_activation", path: "/activation/{token}")]
    public function activate(HashRepository $hashRepository, string $token, LoginCredentialsRepository $loginCredentialsRepository, EntityManagerInterface $entityManager): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute("home");
        }
        
        $hash = $hashRepository->findOneBy(["hash" => $token]);

        if(!$hash) {
            $this->addFlash("warning", "Une erreur est survenue. Le lien d'activation n'est pas valide.");
            return $this->redirectToRoute("home", ["_fragment" => "home__messages"]);
        }

        $user = $loginCredentialsRepository->findOneBy(["id" => $hash->getIdLoginCredentials()]);

        $user->setIsActive(true);
        $hash->setIsActive(false);
            
        $entityManager->persist($user);
        $entityManager->persist($hash);
        $entityManager->flush();
        
        $this->addFlash("success", "Votre compte est activé !");       

        return $this->redirectToRoute("home", ["_fragment" => "home__messages"]);
    }


    #[Route(name: "resend_activation", path: "/send-activation")]
    public function resendActivation(Request $request, LoginCredentialsRepository $loginCredentialsRepository, EntityManagerInterface $entityManager, MailerInterface $mailer): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute("home");
        }

        if ($request->isMethod("POST")) {
            $emailUser = $request->get("resend_activation_mail");

            $user = $loginCredentialsRepository->findOneBy(["email" => $emailUser]);

            if(!$user) {
                $this->addFlash("warning", "L'utilisateur n'existe pas.");
                return $this->redirectToRoute("resend_activation");
            } elseif($user->isIsActive() === true) {
                $this->addFlash("warning", "L'utilisateur est déjà actif.");
                return $this->redirectToRoute("resend_activation");
            }

            $token = bin2hex(random_bytes(64));
            $hash = new Hash();
            $hash->setIdLoginCredentials($user);
            $hash->setHash($token);
            $hash->setIsActive(true);

            $entityManager->persist($user);
            $entityManager->persist($hash);
            $entityManager->flush();

            $email = new Email();
            $email->from("noreply@snowtricks.com");
            $email->to($emailUser);
            $email->subject("Activation de votre compte SnowTricks");
            $email->html("<a href='".$this->generateUrl("register_activation", ["token" => $token], UrlGeneratorInterface::ABSOLUTE_URL)."'>Cliquez ici pour activer votre compte !</a>");
            $mailer->send($email);

            $this->addFlash("success", "Un mail de réactivation de compte vous a été envoyé !");

            return $this->redirectToRoute("resend_activation");
        }

        return $this->render("pages/members/resend_activation.html.twig");
    }
}