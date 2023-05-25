<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\LoginCredentials;
use App\Form\RegistrationFormType;


class RegistrationController extends AbstractController {
    #[Route("/inscription", name: "register")]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response {
        $user = new LoginCredentials();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($userPasswordHasher->hashPassword(
                $user, $form->get("password")->getData()
            ));

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute("home");
        }


        return $this->render("pages/members/register.html.twig", [
            "form" => $form->createView(),
        ]);
    }
}
