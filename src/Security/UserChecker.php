<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\LoginCredentials;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;


class UserChecker implements UserCheckerInterface {
    public function __construct(private UrlGeneratorInterface $urlGenerator) {}

    public function checkPreAuth(UserInterface $user): void {
        if($user->isIsActive() === false) {
            $message = "Votre compte n'est pas activé. Veuillez l'activer.";
            throw new CustomUserMessageAccountStatusException($message);
        }
    }

    public function checkPostAuth(UserInterface $user): void {}
}