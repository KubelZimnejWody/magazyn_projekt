<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Warehouse;
use App\Repository\UserRepository;
use App\Repository\WarehouseRepository;
use http\Client\Curl\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;


class ReleaseItemFormType extends AbstractType
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
            ->add('przyjmij', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
