<?php

namespace App\Form;

use App\Entity\RecipeStep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeStepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'étape",
                'required' => false,
                'label_attr' => ['class' => 'visually-hidden'],
                'row_attr' => ['class' => 'my-1'],
                'attr' => ['class' => 'form-control-sm'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'label_attr' => ['class' => 'visually-hidden'],
                'row_attr' => ['class' => 'my-1'],
                'attr' => ['rows' => 6],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeStep::class
        ]);
    }
}
