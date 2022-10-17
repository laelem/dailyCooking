<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\QuantityType;
use App\Entity\StockIngredient;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredient', EntityType::class, [
                'label' => 'Ingrédient',
                'class' => Ingredient::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')->orderBy('i.name', 'ASC');
                },
                'attr' => ['class' => 'select2'],
            ])
            ->add('quantityNumber', NumberType::class, [
                'label' => 'Quantité',
                'required' => false,
            ])
            ->add('quantityType', EntityType::class, [
                'label' => 'Mesure',
                'required' => false,
                'class' => QuantityType::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('q')->orderBy('q.name', 'ASC');
                },
            ])
            ->add('expiresAt', DateType::class, [
                'label' => "Date d'expiration",
                'required' => false,
                'years' => range(date('Y')-1, date('Y')+6),
            ])
            ->add('stockStatus', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => StockIngredient::getStockStatusOptions(),
                'required' => false,
            ])
            ->add('comment', TextType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StockIngredient::class,
        ]);
    }
}
