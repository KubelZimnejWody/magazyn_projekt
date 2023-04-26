<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', EntityType::class,[
                'class' => User::class,
//                'multiple' => true,
                'choice_label' => 'username',
                'placeholder' => 'wybierz uzytkownika',
                'required' => true,
                'attr' => [
                    'class' => 'select2',
                ],
                'query_builder' => function (UserRepository $ur)
                {
                    return $ur->getUsers();
                }
            ])
            ->add('usun', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
