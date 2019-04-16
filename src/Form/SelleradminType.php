<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelleradminType extends AbstractType
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
                    'label' => 'Prénom'
                ]
            )
            ->add('lastname',
                TextType::class,
                [
                    'label' => 'Nom'
                ]
            )
            ->add('shopName',
                TextType::class,
                [
                    'label' => 'Enseigne'
                ]
            )
            ->add('presentation',
                TextareaType::class,
                [
                    'label' => 'Description',
                    // champ optionnel
                    'required' => false
                ]
            )
            ->add('address',
                TextType::class,
                [
                    'label' => 'Adresse'
                ]
            )

            ->add('email',
                EmailType::class,
                [
                    'label' => 'Email'
                ]
            )
            ->add('role', ChoiceType::class,[
                'expanded' => true,
                'choices' => [
                    'Acheteur' => 'ROLE_BUYER',
                    'Vendeur' => 'ROLE_SELLER'
                ]
            ])
        ;
    }

}
