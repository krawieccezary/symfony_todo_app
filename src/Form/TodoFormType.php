<?php

namespace App\Form;

use App\Entity\Todo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TodoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis'
            ])
            ->add('date', DateType::class, [
                'label' => 'Data'
            ])
            ->add('priority')
            ->add('is_period', CheckboxType::class, [
                'required' => false,
                'label' => 'Zadanie cykliczne',
                'label_attr' => [
                    'class' => 'checkbox-inline checkbox-switch',
                ],
                'attr' => [
                    'data-bs-toggle' => 'collapse',
                    'data-bs-target' => '#periodOptions',
                    'aria-expanded' => 'false',
                    'aria-controls' => 'periodOptions'
                ]
            ])
            ->add('period_from', DateType::class, [
                'label' => 'Od'
            ])
            ->add('period_to', DateType::class, [
                'label' => 'Do'
            ])
            ->add('period_time', TimeType::class, [
                'label' => 'Godzina'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Todo::class,
        ]);
    }
}
