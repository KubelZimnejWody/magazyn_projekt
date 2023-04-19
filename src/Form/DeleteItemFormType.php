<?php

namespace App\Form;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', EntityType::class, [
                'class' => Item::class,
                'multiple'=> true,
                'choice_label' => 'name',
                'placeholder' => 'wybierz artykul',
                'required' => true,
                'attr' => [
                    'class' => 'select2',
                ],
                'query_builder' => function(ItemRepository $ir)
                {
                    return $ir->getItems();
                }
            ])
            ->add('usun', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
