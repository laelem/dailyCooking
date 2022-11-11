<?php

namespace App\Form;

use App\Entity\QuantityType;
use App\Entity\RecipeIngredientPortionNumber;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeIngredientPortionNumberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantityNumber', NumberType::class, [
                'label' => 'Quantité',
                'label_attr' => ['class' => 'visually-hidden'],
                'required' => false,
                'row_attr' => ['class' => 'my-1'],
                'attr' => ['class' => 'form-control-sm'],
            ])
            ->add('quantityType', EntityType::class, [
                'label' => 'Mesure',
                'label_attr' => ['class' => 'visually-hidden'],
                'required' => false,
                'class' => QuantityType::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('q')->orderBy('q.name', 'ASC');
                },
                'row_attr' => ['class' => 'my-1'],
                'attr' => ['class' => 'form-select-sm'],
            ])
            ->add('comment', TextType::class, [
                'label' => 'Commentaire',
                'label_attr' => ['class' => 'visually-hidden'],
                'required' => false,
                'row_attr' => ['class' => 'my-1'],
                'attr' => ['class' => 'form-control-sm'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredientPortionNumber::class
        ]);
    }
}
