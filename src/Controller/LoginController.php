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


    #[Route(path: "/deconnexion", name: "logout")]
    public function logout(): void {}
}
