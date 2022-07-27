<?php

namespace App\Form;

use App\Entity\GameGenre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Titre du jeu',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Description du jeu',
                ],
            ])
            ->add('type', EntityType::class, [
                'label' => 'type',
                'required' => true,
                'multiple' => true,
                'class' => GameGenre::class,
                'choice_label' => 'name',
                'attr' => [
                    'placeholder' => 'Image du jeu',
                ],
            ])
            ->add('promotion', NumberType::class, [
                'label' => 'Promotion (en%)',
                'required' => false ,
                'attr' => [
                    'max' => 100,
                    'min' => 0,

                    'placeholder' => '20%',
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'prix en €',
                'required' => false ,
                'attr' => [
                    'placeholder' => 'prix en €',
                ],
            ])
            ->add('plateformes', ChoiceType::class, [
                'choices' => [
                    'PC' => 'PC',
                    'ps5' => 'ps5',
                    'ps4' => 'ps4',
                    'xbox360' => 'xbox360',
                    'xboxone' => 'xboxone',
                    'switch' => 'switch',],
                'multiple' => true,
                'expanded' => true,
                ])

            ->add('dateRelease', DateTimeType::class, [
                'label' => 'Date de sortie',
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'placeholder' => 'Date de de sortie',
                ],
            ])
            ->add('attachement', FileType::class, [
                'mapped'=> false,
                'label' => 'Image',
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'accept' => 'image/*',
                    'multiple' => 'multiple',
                    'class'   => 'pomme',
                    'placeholder' => 'Image du jeu',
                ],
            ])

            ->add('save', SubmitType::class, [
                'label' => '➕ Ajouter le jeu',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
        ;
    }
}