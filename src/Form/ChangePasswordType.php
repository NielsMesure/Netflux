<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[
                'disabled'=>true,
                'label'=>'Prénom',
                'attr'=>[
                    'class'=>'research-field',
                ]

            ])
            ->add('lastname', TextType::class,[
                'disabled'=>true,
                'label'=>'Nom',
                'attr'=>[
                    'class'=>'research-field',
                ]
            ])
            ->add('email',EmailType::class,[
                'disabled'=>true,
                'label'=>'Adresse Email',
                'attr'=>[
                    'class'=>'research-field',
                ]
            ])
            ->add('old_password',PasswordType::class,[
                'label'=>'Mot de Passe actuel',
                'mapped'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez votre mot de passe',
                    'class'=>'research-field',
                ]
            ])

            ->add('new_password',RepeatedType::class,[
                'type'=> PasswordType::class,
                'mapped'=>false,
                'invalid_message'=>"Le mot de passe n'est pas le même",
                'label'=> 'Nouveau mot de passe',
                'required'=>true,
                'first_options'=>[
                    'label'=>'Mot de Passe',
                    'attr'=>[
                        'placeholder'=>'Merci de saisir votre nouveau mot de passe',
                        'class'=>'research-field',
                    ]
                ],
                'second_options'=>[
                    'label'=>'Confirmation nouveau mot de passe',
                    'attr'=>[
                        'placeholder'=>'Merci de confirmer votre nouveau Mot de Passe',
                        'class'=>'research-field',
                    ]
                ],
            ])
            ->add('submit',SubmitType::class,[
                'label'=>"Mettre à jour",
                'attr'=>[
                    'placeholder'=>'Merci de confirmer votre nouveau Mot de Passe',
                    'class'=>'custom-submit',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
