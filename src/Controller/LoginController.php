<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends AbstractController {
    #[Route(path: "/connexion", name: "login")]
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


    #[Route(path: "/mot-de-passe-oublie", name: "forgot_password")]
    public function forgotPassword(): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute("home");
        }

        return $this->render("pages/members/forgot_password.html.twig");
    }


    #[Route(path: "/mot-de-passe-oublie-form/{token}", name: "reset_password")]
    public function resetPassword(): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute("home");
        }

        return $this->render("pages/members/reset_password.html.twig");
    }


    #[Route(path: "/deconnexion", name: "logout")]
    public function logout(): void {}
}
