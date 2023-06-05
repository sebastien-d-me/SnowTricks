<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\LoginCredentials;


class RegistrationFormType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder->add("username", TextType::class, [
            "constraints" => [
                new Length([
                    "max" => 4096,
                    "min" => 2,
                    "minMessage" => "Votre nom d'utilisateur doit contenir au moins {{ limit }} caractères."
                ]),
                new NotBlank([
                    "message" => "Veuillez écrire votre nom d'utilisateur."
                ])
            ],
            "error_bubbling" => true
        ]);

        $builder->add("email", EmailType::class, [
            "constraints" => [
                new NotBlank([
                    "message" => "Veuillez écrire votre adresse email.",
                ])
            ],
            "error_bubbling" => true
        ]);

        $builder->add("password", PasswordType::class, [
            "constraints" => [
                new Length([
                    "max" => 4096,
                    "min" => 6,
                    "minMessage" => "Votre mot de passe doit contenir au moins {{ limit }} caractères.",
                        
                ]),
                new NotBlank([
                    "message" => "Veuillez écrire votre mot de passe.",
                ])
            ],
            "error_bubbling" => true,
            "mapped" => false
        ]);
    }

    
    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            "attr" => [
                "class" => "form__block",
            ],
            "data_class" => LoginCredentials::class
        ]);
    }
}
