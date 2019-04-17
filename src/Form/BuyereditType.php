<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuyereditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('image',
        FileType::class,
        [
            'label' => 'Image de profil',
            // champ optionnel
            'required' => false
        ]
    )
        ->add('firstname',
            TextType::class,
            [
                'label' => 'Prénom',
                // champ optionnel
                'required' => false
            ]
        )
        ->add('lastname',
            TextType::class,
            [
                'label' => 'Nom',
                // champ optionnel
                'required' => false
            ]
        )
        ->add('presentation',
            TextareaType::class,
            [
                'label' => 'Description, votre expérience de collectionneur',
                // champ optionnel
                'required' => false
            ]
        )
        ->add('address',
            TextType::class,
            [
                'label' => 'Adresse',
                // champ optionnel
                'required' => false
            ]
        )
        ->add('plainPassword',
            // 2 champs qui doivent avoir la même valeur
            RepeatedType ::class,
            [
                // ... de type password
                'type' => PasswordType::class,
                // options du 1er des 2 champs
                'first_options' => [
                    'label' => 'Mot de passe'
                ],
                // options du second champ
                'second_options' => [
                    'label' => 'Confirmation du mot de passe'
                ],
                // message si les 2 champs n'ont pas la même valeur
                'invalid_message' => 'La confirmation ne correspond pas au mot de passe'
            ]
        )
        ->add('email',
            EmailType::class,
            [
                'label' => 'Email',
                // champ optionnel
                'required' => false
            ]
        )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
