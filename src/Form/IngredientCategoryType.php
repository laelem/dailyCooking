<?php

namespace App\Form;

use App\Entity\IngredientCategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('positionEnum', ChoiceType::class, [
                'label' => 'Position',
                'choices' => IngredientCategory::POSITION_ENUM_CHOICES,
                'expanded' => true,
                'label_attr' => ['class' => 'radio-inline'],
            ])
            ->add('beforeCategory', EntityType::class, [
                'label' => 'Catégorie',
                'label_attr' => ['class' => 'visually-hidden'],
                'row_attr' => ['class' => 'mb-0'],
                'required' => false,
                'class' => IngredientCategory::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $qb = $er->createQueryBuilder('c');

                    if ($options['currentCategoryId']) {
                        $qb->andWhere('c.id <> :id')->setParameter('id', $options['currentCategoryId']);
                    }

                    return $qb->orderBy('c.position', 'ASC');
                },
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IngredientCategory::class,
            'currentCategoryId' => null,
            'validation_groups' => function (FormInterface $form) {
                $groups = ['Default'];

                /** @var IngredientCategory $data */
                $data = $form->getData();

                if (IngredientCategory::POSITION_AFTER == $data->getPositionEnum()) {
                    $groups[] = 'categoryBasedPosition';
                }

                return $groups;
            },
        ]);
    }
}
