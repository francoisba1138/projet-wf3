<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\Game;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdadminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seller')



            ->add('date',
                DateType::class,
                [
                    'label' => 'Date',
                    'widget' => 'single_text'


                ]
            )


            ->add('game',
                EntityType::class,
                [
                    'label' => 'Jeu',
                    'class' => Game::class

                ]
            )

            ->add('price',
                IntegerType::class,
                [
                    'label' => 'Prix'
                ]
            )

            ->add('cond',
                TextType::class,
                [
                    'label' => 'Condition'
                ]
            )

            ->add('status',
                TextType::class,
                [
                    'label' => 'Statut'
                ]
            )
            ->add('title',
                TextType::class,
                [
                    'label' => 'Titre'
                ]
            )
            ->add('content',
                TextType::class,
                [
                    'label' => 'Description'
                ]
            )
            ->add('buyer',
                EntityType::class,
                [
                    'label' => 'Acheteur',
                    'class' => User::class
                ]
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
