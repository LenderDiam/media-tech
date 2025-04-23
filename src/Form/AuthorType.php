<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Document;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('lastname', null, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('documents', EntityType::class, [
                'class' => Document::class,
                'choice_label' => 'title',
                'multiple' => true,
                'label' => 'Documents associés',
                'attr' => [
                    'class' => 'form-select',
                ],
                'row_attr' => ['class' => 'mb-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
