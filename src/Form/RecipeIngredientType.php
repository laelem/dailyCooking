<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\QuantityType;
use App\Entity\RecipeIngredient;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredient', EntityType::class, [
                'label' => 'Ingrédient',
                'label_attr' => ['class' => 'visually-hidden'],
                'placeholder' => '',
                'class' => Ingredient::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')->orderBy('i.name', 'ASC');
                },
                'row_attr' => ['class' => 'my-1'],
                'attr' => ['class' => 'select2'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredient::class
        ]);
    }
}
