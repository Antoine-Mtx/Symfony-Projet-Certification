<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre pseudo ne peut pas être vide',
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre email ne peut pas être vide',
                    ]),
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les conditions',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez accepter nos conditions',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // au lieu d'être fixé sur l'objet directement, ceci est lu et encodé dans le contrôleur
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmation mot de passe',
                ],
                'attr' => [
                    // 'autocomplete' => 'new-password',
                    'placeholder' => 'Votre mot de passe doit compter 8 caractères minimum et contenir au moins : une lettre majuscule, une lettre minuscule, un chiffre, un caractère spécial',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Aucun mot de passe renseigné',
                    ]),
                    new Regex([
                        // Au moins une lettre de l'alphabet Latin en majuscule : (?=.*?[A-Z])
                        // Au moins une lettre de l'alphabet Latin en minuscule : (?=.*?[a-z])
                        // Au moins un chiffre : (?=.*?[0-9])
                        // Au moins un caractère spécial : (?=.*?[#?!@$%^&*-])
                        // Au moins 12 caractères : .{12,}
                        'pattern' => '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{12,}$/',
                        'match' => true,
                        'message' => 'Votre mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // la longueur maximale autorisée par Symfony pour des raisons de sécurité est de 4096, cependant, certaines personnes recommandent de la mettre à 200
                        'max' => 200,
                        'maxMessage' => 'Votre mot de passe ne doit pas dépasser {{ limit }} caractères',
                    ]),
                    // new NotEqualTo([
                    //     'propertyPath' => 'pseudo',
                    //     'message' => "Votre mot de passe ne doit pas être le même que votre nom d'utilisateur"
                    // ]),
                    // new NotEqualTo([
                    //     'propertyPath' => 'email',
                    //     'message' => "Votre mot de passe ne doit pas être le même que votre nom d'utilisateur"
                    // ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
