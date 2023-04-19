<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Warehouse;
use App\Repository\WarehouseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use Symfony\Component\Security\Core\Security;

class EraseItemFormType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name', EntityType::class,[
                'class' => Warehouse::class,
                'multiple'=> true,
                'choice_label' => 'name',
                'placeholder' => 'Choose a warehouse',
                'required' => true,
                'attr' => [
                    'class' => 'select2',
                ],
                'query_builder' => function (WarehouseRepository $wr)
                {
                    return  $wr->getWarehousesByUserId($this->security->getUser()->getId());
                }
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Warehouse::class,
        ]);
    }
}
