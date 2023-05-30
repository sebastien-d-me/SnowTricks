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
use App\Entity\Hash;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class RegistrationController extends AbstractController {
    #[Route("/inscription", name: "register")]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response {
        $user = new LoginCredentials();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($userPasswordHasher->hashPassword(
                $user, $form->get("password")->getData()
            ));
            $user->setIsActive(false);           

            $entityManager->persist($user);
            $entityManager->flush();


            $token = bin2hex(random_bytes(64));
            $hash = new Hash();
            $hash->setIdLoginCredentials($user);
            $hash->setHash($token);
            $hash->setIsActive(true);

            $entityManager->persist($hash);
            $entityManager->flush();

            $email = (new Email())
                ->from("noreply@snowtricks.com")
                ->to($form->get("email")->getData())
                ->subject("Activation de votre compte")
                ->html("<a href='".$this->generateUrl("user_activation", ["token" => $token], UrlGeneratorInterface::ABSOLUTE_URL)."'>Cliquez ici pour activer votre compte !</a>");
            $mailer->send($email);


            return $this->redirectToRoute("home");
        }


        return $this->render("pages/members/register.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    #[Route("/activation/{token}", name: "user_activation")]
    public function activate(EntityManagerInterface $entityManager, string $token): Response {
        $EM = $entityManager->getRepository(Hash::class);
        $hash = $EM->findOneBy(["hash" => $token]);

        if($hash && $hash->isIsActive() === true) {
            $EM = $entityManager->getRepository(LoginCredentials::class);
            $user = $EM->findOneBy(["id" => $hash->getIdLoginCredentials()]);
            $user->setIsActive(true);

            $entityManager->persist($user);
            $entityManager->flush();

            $hash->setIsActive(false);
            $entityManager->persist($hash);
            $entityManager->flush();
        }

        return $this->redirectToRoute("home");
    }
}
