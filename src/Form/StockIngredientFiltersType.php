<?php

namespace App\Form;

use App\Entity\IngredientCategory;
use App\Entity\StockIngredientFilters;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockIngredientFiltersType extends AbstractType
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
            'data_class' => StockIngredientFilters::class,
        ]);
    }
}
