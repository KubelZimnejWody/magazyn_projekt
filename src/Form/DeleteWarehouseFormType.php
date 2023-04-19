<?php

namespace App\Form;

use App\Entity\Warehouse;
use App\Repository\WarehouseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteWarehouseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', EntityType::class, [
                'class' => Warehouse::class,
                'multiple'=> true,
                'choice_label' => 'name',
                'placeholder' => 'wybierz magazyn',
                'required' => true,
                'attr' =>[
                    'class' => 'select2',
                ],
                'query_builder' => function (WarehouseRepository $wr)
                {
                    return $wr->getWerhouses();
                }
            ])
            ->add('usun', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Warehouse::class,
        ]);
    }
}
