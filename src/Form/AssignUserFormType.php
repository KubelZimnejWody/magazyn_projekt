<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Warehouse;
use App\Repository\UserRepository;
use App\Repository\WarehouseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'wybierz uzytkownika',
                'required' => true,
                'attr' =>[
                    'class'=> 'select2'
                ],
                'query_builder' => function (UserRepository $ur)
                {
                    return $ur->getUsers();
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zatwierdź',
                'attr' =>[
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
