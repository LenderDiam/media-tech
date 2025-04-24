<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Publisher;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('langage', ChoiceType::class, [
                'choices' => [
                    'Français' => 'fr',
                    'Anglais' => 'en',
                ],
                'label' => 'Langue du document',
                'attr' => [
                    'class' => 'form-select'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
            ])
            ->add('thumbnailUrl', null, [
                'label' => 'Lien photo',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('publicationDate', null, [
                'widget' => 'single_text',
                'label' => 'Date de publication',
                'attr' => [
                    'class' => 'form-control'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
            ])
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'choice_label' => function (Author $author) {
                    return trim($author->getFirstname() . ' ' . $author->getLastname());
                },
                'multiple' => true,
                'attr' => [
                    'class' => 'form-select',
                ],
                'row_attr' => ['class' => 'mb-3'],
                'required' => false,
            ])
            ->add('publishers', EntityType::class, [
                'class' => Publisher::class,
                'multiple' => true,
                'label' => 'Éditeurs',
                'choice_label' => function (Publisher $publisher) {
                    if ($publisher->getName()) {
                        return $publisher->getName();
                    }
            
                    return trim($publisher->getFirstname() . ' ' . $publisher->getLastname());
                },
                'attr' => [
                    'class' => 'form-select',
                ],
                'row_attr' => ['class' => 'mb-3'],
                'required' => false,
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'multiple' => true,
                'label' => 'Utilisateurs l\'ayant en favori',
                'attr' => [
                    'class' => 'form-select',
                ],
                'row_attr' => ['class' => 'mb-3'],
                'required' => false,
            ]);
        ;
    }
}
