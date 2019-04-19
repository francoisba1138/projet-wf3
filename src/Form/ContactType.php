<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstname',
                TextType::class,
                [
                    'label' => 'Prénom',
                    //Contrainte
                    'constraints' =>[
                        new NotBlank([
                            'message' => 'Le prénom est obligatoire'
                        ])
                    ]
                ]
            )

            ->add(
                'lastname',
                TextType::class,
                [
                    'label' => 'Nom',
                    //Contrainte
                    'constraints' =>[
                        new NotBlank([
                            'message' => 'Le nom est obligatoire'
                        ])
                    ]
                ]
            )

            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'E-mail',
                    //Contrainte
                    'constraints' =>[
                        new NotBlank([
                            'message' => 'L\'e-mail est obligatoire'
                        ]),
                        new Email([
                            'message' => 'Cet e-mail n\'est pas valide'
                        ])
                    ]
                ]
            )

            ->add(
                'subject',
                TextType::class,
                [
                    'label' => 'Sujet',
                    //Contrainte
                    'constraints' =>[
                        new NotBlank([
                            'message' => 'Le sujet est obligatoire'
                        ])
                    ]
                ]
            )

            ->add(
                'content',
                TextareaType::class,
                [
                    'label' => 'Votre message',
                    //Contrainte
                    'constraints' =>[
                        new NotBlank([
                            'message' => 'Le message est obligatoire'
                        ]),
                        new Length([
                            'min' => '20',
                            'minMessage' => 'Le message doit faire au minimum {{ limit }} caractères '
                        ])
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //
        ]);
    }
}
