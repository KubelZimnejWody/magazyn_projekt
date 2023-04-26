<?php

namespace App\Form;

use App\Entity\Warehouse;
use App\Repository\WarehouseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\Translation\t;

class AssignUserWarehouseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('warehouse', EntityType::class,[
                'class' => Warehouse::class,
                'choice_label' => 'name',
                'placeholder' => 'wybierz magazyn',
                'required' => true,
                'attr' =>[
                    'class' => 'select2',
                ]
            ])
            ->add('wybierz', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
