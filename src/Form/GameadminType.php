<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class GameadminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',
                TextType::class,
                [
                    'label' => 'Titre du Jeu'
                ]
            )

            ->add('platform',
                TextType::class,
                [
                    'label' => 'Plateforme'
                ]
            )

            ->add('date',
                DateType::class,
                [
                    'label' => 'Date',



                ]
            )

            ->add('cover',
                FileType::class,
                [
                    'label' => 'Visuel',
                    // champ optionnel
                    'required' => false
                ]
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
