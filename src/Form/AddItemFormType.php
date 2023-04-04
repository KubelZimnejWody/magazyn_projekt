<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Warehouse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\ItemRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('quantity', NumberType::class)
            ->add('unit', TextType::class)
            ->add('vat', NumberType::class)
            ->add('price', NumberType::class)
            ->add('warehouse', EntityType::class,[
                'class' => Warehouse::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose a warehouse',
                'required' => true,
                'attr' => [
                    'class' => 'select2',
                ]
            ])
            ->add('dodaj', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
