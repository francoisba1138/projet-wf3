<?php

namespace App\Form;

use App\Entity\Game;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

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
                    'label' => 'Plate-forme'
                ]
            )

            ->add('date',
                DateTime::class,
                [
                    'label' => 'Date'
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
