<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\IngredientCategory;
use App\Entity\IngredientTag;
use App\Entity\QuantityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => IngredientCategory::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.position', 'ASC');
                },
            ])
            ->add('defaultQuantityType', EntityType::class, [
                'label' => 'Type de quantité',
                'required' => false,
                'class' => QuantityType::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('q')->orderBy('q.name', 'ASC');
                },
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Tags',
                'required' => false,
                'class' => IngredientTag::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')->orderBy('t.name', 'ASC');
                },
                'multiple' => true,
                'attr' => ['class' => 'select2'],
            ])
            ->add('whereToKeep', ChoiceType::class, [
                'label' => 'Conservation',
                'choices' => Ingredient::getWhereToKeepOptions(),
                'expanded' => true,
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
