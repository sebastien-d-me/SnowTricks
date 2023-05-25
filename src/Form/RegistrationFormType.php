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
        $builder
            ->add("username", TextType::class, [
                "error_bubbling" => true,
                "constraints" => [
                    new NotBlank([
                        "message" => "Veuillez écrire votre nom d'utilisateur.",
                    ]),
                    new Length([
                        "min" => 2,
                        "minMessage" => "Votre nom d'utilisateur doit contenir au moins {{ limit }} caractères.",
                        "max" => 4096,
                    ])
                ],
            ])
            ->add("email", EmailType::class, [
                "error_bubbling" => true,
                "constraints" => [
                    new NotBlank([
                        "message" => "Veuillez écrire votre adresse email.",
                    ])
                ]
            ])
            ->add("password", PasswordType::class, [
                "error_bubbling" => true,
                "mapped" => false,
                "constraints" => [
                    new NotBlank([
                        "message" => "Veuillez écrire votre mot de passe.",
                    ]),
                    new Length([
                        "min" => 6,
                        "minMessage" => "Votre mot de passe doit contenir au moins {{ limit }} caractères.",
                        "max" => 4096,
                    ])
                ],
            ])
            ->add("confirmPassword", PasswordType::class, [
                "error_bubbling" => true,
                "mapped" => false,
                "constraints" => [
                    new NotBlank([
                        "message" => "Veuillez confirmer votre mot de passe.",
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            "data_class" => LoginCredentials::class,
        ]);
    }
}
