<?php

namespace App\Form;

use App\Entity\Audio;
use App\Enum\AudioFormat;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AudioType extends DocumentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('duration', null, [
                'widget' => 'single_text',
                'label' => 'Durée',
                'attr' => [
                    'class' => 'form-control'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
            ])
            ->add('format', ChoiceType::class, [
                'choices' => AudioFormat::cases(),
                'choice_label' => fn(AudioFormat $format) => $format->label(),
                'choice_value' => fn(?AudioFormat $format) => $format?->value,
                'label' => 'Format audio',
                'placeholder' => 'Choisir un format...',
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
            'data_class' => Audio::class,
        ]);
    }
}
