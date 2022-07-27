<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Titre de l\'article',
                ],
            ])
            ->add('pubDate', DateTimeType::class, [
                'label' => 'Date de publication',
                'required' => true,
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'placeholder' => 'Date de publication',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Description de l\'article',
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Contenu de l\'article',
                ],
            ])
            ->add('attachement', FileType::class, [
                'mapped'=> false,
                'label' => 'Image',
                'required' => false,
                'attr' => [
                    'class'   => 'pomme',
                    'placeholder' => 'Image de l\'article',
                ],
            ])

            ->add('save', SubmitType::class, [
                'label' => '➕ Ajouter l\'article',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
        ;
    }
}