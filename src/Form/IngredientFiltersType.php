<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\IngredientCategory;
use App\Entity\IngredientFilters;
use App\Entity\Tag;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'label_attr' => ['class' => 'text-end'],
                'row_attr' => ['class' => 'align-items-center'],
                'attr' => ['class' => 'form-control-sm'],
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'required' => false,
                'placeholder' => 'Toutes',
                'class' => IngredientCategory::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.position', 'ASC');
                },
                'label_attr' => ['class' => 'text-end'],
                'row_attr' => ['class' => 'align-items-center'],
                'attr' => ['class' => 'form-select-sm'],
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Tags',
                'required' => false,
                'class' => Tag::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')->orderBy('t.name', 'ASC');
                },
                'multiple' => true,
                'label_attr' => ['class' => 'text-end'],
                'row_attr' => ['class' => 'align-items-center'],
                'attr' => ['class' => 'form-select-sm select2'],
            ])
            ->add('whereToKeep', ChoiceType::class, [
                'label' => 'Conservation',
                'required' => false,
                'placeholder' => 'Indifférent',
                'choices' => array_flip(Ingredient::WHERE_TO_KEEP_OPTIONS),
                'label_attr' => ['class' => 'text-end'],
                'row_attr' => ['class' => 'align-items-center'],
                'attr' => ['class' => 'form-select-sm'],
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
            'data_class' => IngredientFilters::class,
        ]);
    }
}
