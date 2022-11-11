<?php

namespace App\Form;

use App\Entity\RecipePortionNumber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipePortionNumberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recipeIngredientPortionNumberList', CollectionType::class, [
                'label' => 'Ingrédients',
                'label_attr' => ['class' => 'visually-hidden'],
                'entry_type' => RecipeIngredientPortionNumberType::class,
                'entry_options' => ['label' => false],
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipePortionNumber::class,
        ]);
    }
}
