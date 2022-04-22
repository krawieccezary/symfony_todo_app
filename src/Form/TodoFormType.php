<?php

namespace App\Form;

use App\Entity\Priority;
use App\Entity\Todo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add('date', DateTimeType::class, [
                'label' => 'Data',
                'widget' => 'single_text',
            ])
            ->add('priority', EntityType::class, [
                'class' => Priority::class,
                'choice_label' => 'title'
            ])
            ->add('isPeriod', CheckboxType::class, [
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
            ->add('periodFrom', DateType::class, [
                'label' => 'Od',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('periodTo', DateType::class, [
                'label' => 'Do',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('periodTime', TimeType::class, [
                'label' => 'Godzina',
                'widget' => 'single_text',
                'required' => false
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
