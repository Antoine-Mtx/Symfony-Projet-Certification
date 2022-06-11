<?php

namespace App\Form;

use App\Entity\Competence;
use App\Form\ComposantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CompetenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule')
            ->add('description')
            ->add('dateCreation')
            ->add('image')
            ->add('icone')
            ->add('synopsis')
            ->add('concepteur')
            ->add('domaine')
            ->add('composants', CollectionType::class, [
                // chaque élément du tableau sera de type "Composant"
                'entry_type' => ComposantType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                // pas de référence à setComposant dans l'entité Competence
                'by_reference' => false,
                // 'choice_label' => 'composant',
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
