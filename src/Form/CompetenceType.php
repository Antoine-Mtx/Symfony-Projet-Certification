<?php

namespace App\Form;

use App\Entity\Composant;
use App\Entity\Competence;
use App\Form\ComposantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CompetenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule')
            ->add('domaine')
            ->add('synopsis')
            ->add('description')
            ->add('imageFilename', FileType::class, [
                'label' => 'Image',
                // unmapped signifie que ce champ n'est associé à aucune propriété de l'entité
                'mapped' => false,
                // on rend ce champ facultatif pour ne pas avoir à retélécharger le fichier à chaque fois qu'on modifie les détails de notre objet
                'required' => false,
                // les champs unmapped ne peuvent pas définir leur validation à l'aide d'annotations dans l'entité associée, on doit donc utiliser les classes de contraintes PHP
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez charger une image valide',
                    ])
                ],
            ])
            ->add('iconeFilename', FileType::class, [
                'label' => 'Icone',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez charger une image valide',
                    ])
                ],
            ])
            ->add('composants', EntityType::class, [
                'class' => Composant::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Competence::class,
        ]);
    }

}

            // ->add('composants', CollectionType::class, [
            //     // chaque élément du tableau sera de type "Composant"
            //     'entry_type' => EntityType::class,
            //     'entry_options' => [
            //         'label' => "choisir composant : ",
            //         'class' => Composant::class
            //     ],
            //     'prototype' => true,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     // pas de référence à setComposant dans l'entité Competence
            //     'by_reference' => false,
            //     // 'choice_label' => 'composant',
            // ])

            // ->add('composants', CollectionType::class, [
            //     // chaque élément du tableau sera de type "Composant"
            //     'entry_type' => ComposantType::class,
            //     'prototype' => true,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     // pas de référence à setComposant dans l'entité Competence
            //     'by_reference' => false,
            //     // 'choice_label' => 'composant',
            // ])
