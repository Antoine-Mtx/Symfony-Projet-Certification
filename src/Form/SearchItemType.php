<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mots', SearchType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control pe-5 bg-transparent',
                    'placeholder' => 'Recherche par mots-clés'
                ]
            ])
            // notre bouton de soumission du formulaire sera une icône de recherche qui se placera à la fin de notre input
            ->add('recherche', SubmitType::class, [
                'attr' => [
                    'class' => 'btn bg-transparent px-2 py-0 position-absolute top-50 end-0 translate-middle-y',
                ],
                'label' => '<i class="fas fa-search fs-6 "></i>',
                'label_html' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'rounded position-relative',
            ],
        ]);
    }
}
