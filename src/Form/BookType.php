<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends DocumentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        
        $builder
            ->add('isbn', null, [
                'label' => 'Isbn',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
            ])
            ->add('pages', null, [
                'label' => 'Nombre de pages',
                'attr' => [
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
