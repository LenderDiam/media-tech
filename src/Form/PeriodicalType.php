<?php

namespace App\Form;

use App\Entity\Periodical;
use App\Enum\PeriodicalFrequency;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PeriodicalType extends DocumentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('frequency', ChoiceType::class, [
                'choices' => PeriodicalFrequency::cases(),
                'choice_label' => fn(PeriodicalFrequency $format) => $format->label(),
                'choice_value' => fn(?PeriodicalFrequency $format) => $format?->value,
                'label' => 'Fréquence de publication',
                'placeholder' => 'Choisir une fréquence...',
                'attr' => [
                    'class' => 'form-select'
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
            'data_class' => Periodical::class,
        ]);
    }
}
