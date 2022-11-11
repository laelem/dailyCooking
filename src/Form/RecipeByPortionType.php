<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\RecipePortionNumber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeByPortionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('portions', CollectionType::class, [
                'label' => 'Portions',
                'label_attr' => ['class' => 'visually-hidden'],
                'entry_type' => RecipePortionNumberType::class,
                'entry_options' => ['label' => false],
                'by_reference' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
