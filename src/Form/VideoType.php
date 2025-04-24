<?php

namespace App\Form;

use App\Entity\Video;
use App\Enum\VideoFormat;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends DocumentType
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
                'choices' => VideoFormat::cases(),
                'choice_label' => fn(VideoFormat $format) => $format->label(),
                'choice_value' => fn(?VideoFormat $format) => $format?->value,
                'label' => 'Format vidéo',
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
            'data_class' => Video::class,
        ]);
    }
}
