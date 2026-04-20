<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                    'maxlength' => 255,
                    'class' => 'form-input'
                ],
                'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire'),
                    new Length(min: 3, max: 255)
                ]
            ])

            ->add('username', TextType::class, [
                'label' => 'Le prénom du joueur',
                'required' => true,
                'attr' => [
                    'placeholder' => 'le prénom',
                    'maxlength' => 255,
                    'class' => 'form-input'
                ],
                'constraints' => [
                    new NotBlank(message: 'Le prénom est obligatoire'),
                    new Length(min: 3, max: 255)
                ]
            ])

            ->add('number', IntegerType::class, [
                'label' => 'Numéro du maillot',
                'required' => true,
                'attr' => [
                    'placeholder' => 'le numéro',
                    'min' => 1,
                    'max' => 99,
                    'class' => 'form-input'
                ],
                'constraints' => [
                    new NotBlank(message: 'Numéro obligatoire'),
                    new Positive(message: 'Le numéro doit être positif')
                ]
            ])

            ->add('score', IntegerType::class, [
                'label' => 'Score',
                'required' => false,
                'attr' => [
                    'placeholder' => 'score',
                    'min' => 0,
                    'class' => 'form-input'
                ],
                'constraints' => [
                    new Positive(message: 'Le score doit être positif')
                ]
            ])

            ->add('team', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir une équipe',
                'required' => false,
                'attr' => [
                    'class' => 'form-input'
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Créer le joueur',
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