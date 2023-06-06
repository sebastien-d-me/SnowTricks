<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LoginCredentialsRepository;
use App\Entity\Hash;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mailer\MailerInterface;


class LoginController extends AbstractController {
    #[Route(name: "login", path: "/connexion")]
    public function login(AuthenticationUtils $authenticationUtils): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute("home");
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("pages/members/login.html.twig", [
            "error" => $error,
            "last_username" => $lastUsername
        ]);
    }


    #[Route(name: "forgot_password", path: "/mot-de-passe-oublie")]
    public function forgotPassword(Request $request, LoginCredentialsRepository $loginCredentialsRepository, EntityManagerInterface $entityManager, MailerInterface $mailer): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute("home");
        }

        if ($request->isMethod("POST")) {
            $username = $request->get("forgot_password_username");

            $user = $loginCredentialsRepository->findOneBy(["username" => $username]);
            
            if(!$user) {
                $this->addFlash("warning", "Le nom d'utilisateur n'existe pas.");
                return $this->redirectToRoute("forgot_password");
            }

            $token = bin2hex(random_bytes(64));
            $hash = new Hash();
            $hash->setIdLoginCredentials($user);
            $hash->setHash($token);
            $hash->setIsActive(true);

            $entityManager->persist($hash);
            $entityManager->flush();

            $email = new Email();
            $email->from("noreply@snowtricks.com");
            $email->to($user->getEmail());
            $email->subject("Modification de votre mot de passe SnowTricks");
            $email->html("<a href='".$this->generateUrl("reset_password", ["token" => $token], UrlGeneratorInterface::ABSOLUTE_URL)."'>Cliquez ici pour changer votre mot de passe !</a>");
            $mailer->send($email);

            $this->addFlash("success", "Un email de changement de mot de passe vous a été envoyé !");

            return $this->redirectToRoute("forgot_password");
        }

        return $this->render("pages/members/forgot_password.html.twig");
    }


    #[Route(name: "reset_password", path: "/mot-de-passe-oublie-form/{token}")]
    public function resetPassword(Request $request): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute("home");
        }

        return $this->render("pages/members/reset_password.html.twig");
    }


    #[Route(name: "logout", path: "/deconnexion")]
    public function logout(): void {}
}
