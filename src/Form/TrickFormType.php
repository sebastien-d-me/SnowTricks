<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\TrickGroup;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use App\Entity\Trick;


class TrickFormType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder->add("name", TextType::class, [
            "label" => "Nom"
        ]);
        $builder->add("description", TextareaType::class, [
            "label" => "Description"
        ]);
        $builder->add("idTrickGroup", EntityType::class, [
            "class" => TrickGroup::class,
            "label" => "Catégorie"
        ]);
        $builder->add("featured", FileType::class, [ 
            "constraints" => [
                new File([
                    "maxSize" => "4096k",
                    "mimeTypes" => [
                        "image/gif",
                        "image/jpg",
                        "image/jpeg",
                        "image/png"
                    ],
                    "mimeTypesMessage" => "Veuillez importer une image",
                ])
            ],
            "label" => "Image à la une",
        ]);
        $builder->add("medias", FileType::class, [ 
            "constraints" => [
                new File([
                    "maxSize" => "4096k",
                    "mimeTypes" => [
                        "image/gif",
                        "image/jpg",
                        "image/jpeg",
                        "image/png",
                        "video/mp4"
                    ],
                    "mimeTypesMessage" => "Veuillez importer une image ou une vidéo",
                ])
            ],
            "label" => "Illustrations et vidéos",
            "multiple" => true
        ]);
    }


    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            "attr" => [
                "class" => "form__block",
            ],
            "data_class" => Trick::class,
        ]);
    }
}