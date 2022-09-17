<?php

namespace App\Form;

use App\Entity\RecipeFilters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => false,
                'label_attr' => ['class' => 'text-end'],
                'row_attr' => ['class' => 'align-items-center'],
                'attr' => ['class' => 'form-control-sm'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Filtrer',
                'row_attr' => ['class' => 'mt-1'],
                'attr' => ['class' => 'btn-sm btn-outline-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeFilters::class,
        ]);
    }
}
