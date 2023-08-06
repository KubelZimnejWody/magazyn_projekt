<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Warehouse;
use App\Entity\WarehouseItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', EntityType::class, [
                'class' => Warehouse::class,
                'choice_label' => 'id',
                'label' => false,
                'placeholder' => 'wybierz magazyn',
                'required'=> true,
                'attr' =>[
                    'class'=> 'select2'
                ],
            ])
            ->add('quantity', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'min' => 1,
                    'placeholder' => 'Ilość'
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'min' => 1,
                    'placeholder' => 'Cena'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zatwierdź',
                'attr' => [
                    'class' => 'btn btn-primary w-100'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
