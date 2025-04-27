<?php

namespace App\Form;

use App\Entity\Basket;
use App\Entity\Copy;
use App\Entity\Document;
use App\Entity\Loan;
use App\Enum\CopyPhysicalCondition;
use App\Enum\CopyState;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CopyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('state', ChoiceType::class, [
                'choices' => CopyState::cases(),
                'choice_label' => fn(CopyState $format) => $format->label(),
                'choice_value' => fn(?CopyState $format) => $format?->value,
                'label' => 'Status de l\'exemplaire',
                'placeholder' => 'Choisir un status...',
                'attr' => [
                    'class' => 'form-select'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
            ])
            ->add('physicalCondition', ChoiceType::class, [
                'choices' => CopyPhysicalCondition::cases(),
                'choice_label' => fn(CopyPhysicalCondition $format) => $format->label(),
                'choice_value' => fn(?CopyPhysicalCondition $format) => $format?->value,
                'label' => 'État de l\'exemplaire',
                'placeholder' => 'Choisir un état...',
                'attr' => [
                    'class' => 'form-select'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
            ])
            ->add('document', EntityType::class, [
                'class' => Document::class,
                'choice_label' => function (Document $document) {
                    return sprintf('Document #%d - %s', $document->getId(), $document->getTitle());
                },
                'attr' => [
                    'class' => 'form-select',
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
            ])
            ->add('baskets', EntityType::class, [
                'class' => Basket::class,
                'choice_label' => function (Basket $basket) {
                    return sprintf('Panier #%d - %s', $basket->getId(), $basket->getUser()->getEmail());
                },
                'label' => 'Panier(s) associé(s)',
                'attr' => [
                    'class' => 'form-select',
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'multiple' => true,
                'required' => false,
            ])
            ->add('loans', EntityType::class, [
                'class' => Loan::class,
                'choice_label' => function (Loan $loan) {
                    return sprintf('Prêt #%d - %s', $loan->getId(), $loan->getUser()->getEmail());
                },
                'label' => 'Emprunt(s) associé(s)',
                'attr' => [
                    'class' => 'form-select',
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Copy::class,
        ]);
    }
}
