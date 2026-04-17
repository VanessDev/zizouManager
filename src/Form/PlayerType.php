<?php

namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du joueur',
                'required' => true,
                'attr' => [
                    'placeholder' => 'le nom',
                    'maxlength' => 255
                ],
                'constraints' => [
                    new NotBlank(message: 'le nom est obligatoire'),
                    new Length(min: 3, max: 255)
                ]
            ])

            ->add('username', TextType::class, [
                'label' => 'Le prénom du joueur',
                'required' => true,
                'attr' => [
                    'placeholder' => 'le prénom',
                    'maxlength' => 255
                ],
                'constraints' => [
                    new NotBlank(message: 'le prénom est obligatoire'),
                    new Length(min: 3, max: 255)
                ]
            ])

            ->add('number', IntegerType::class, [
                'label' => 'Numéro du maillot',
                'required' => true,
                'attr' => [
                    'placeholder' => 'le numéro',
                    'min' => 1,
                    'max' => 99
                ],
                'constraints' => [
                    new NotBlank(message: 'numéro obligatoire'),
                    new Positive(message: 'le numéro doit être positif')
                ]
            ])

            ->add('score', IntegerType::class, [
                'label' => 'Score',
                'required' => false,
                'attr' => [
                    'placeholder' => 'score',
                    'min' => 0
                ],
                'constraints' => [
                    new Positive(message: 'le score doit être positif')
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'créer le joueur',
                'attr' => [
                    'class' => 'btnForm'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
    
}