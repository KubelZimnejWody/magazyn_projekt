<?php

namespace App\Form;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReleaseItemWarehouseFormType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', EntityType::class,[
                'class' => Item::class,
                'choice_label' => 'id',
                'placeholder' => 'choose an item id',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'select2',
                ],
                'query_builder' => function (ItemRepository $ir) use ($options)
                {
                    return $ir->getItemsByWarehouseId($options['warehouseId']);
                }
            ])
            ->add('quantity', EntityType::class)
            ->add('unit', TextType::class, [
                'class' => Item::class,
                'choice_label' => 'unit',
                'placeholder' => 'choose an item id',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->add('vat', NumberType::class)
            ->add('price', NumberType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'warehouseId' => null,
        ]);
    }
}
