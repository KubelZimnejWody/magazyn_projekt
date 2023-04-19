<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Warehouse;
use App\Repository\ItemRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;

class EraseItemWarehouseFormType extends AbstractType

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
            ->add('quantity',NumberType::class)
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
