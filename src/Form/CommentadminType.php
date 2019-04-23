<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Game;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentadminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title'
            )
            ->add('content',
                TextType::class,
                [
                    'label' => 'Description'
                ]
            )

            ->add('author',
                EntityType::class,
                [
                    'label' => 'Auteur',
                    'class' => User::class
                ]
            )

            ->add('Target',
                EntityType::class,
                [
                    'label' => 'Destinataire',
                    'placeholder' => '',
                    'class' => User::class
                ]
            )

            ->add('Game',
                EntityType::class,
                [
                    'label' => 'Jeu',
                    'placeholder' => '',
                    'class' => Game::class

                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
