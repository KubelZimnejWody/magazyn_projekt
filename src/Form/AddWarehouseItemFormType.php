<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Warehouse;
use App\Entity\WarehouseItem;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use App\Repository\WarehouseRepository;
use http\Client\Curl\User;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Security\Core\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\File;


class AddWarehouseItemFormType extends AbstractType
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('item', EntityType::class, [
                'class' => Item::class,
                'required' => true,
                'choice_label' => 'name',
                'query_builder' => function (ItemRepository $ir) use ($options)
                {
                    return $ir->getItemsNotInWarehouse($options['warehouseId']);
                }
            ])
            ->add('quantity', NumberType::class, [
                'required' => 'true',
                'label' => false,
                'attr' => [
                    'placeholder' => "Ilość"
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
            ->add('invoice', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf'
                        ],
                        'mimeTypesMessage' => 'Prosze podać odpowiedni plik PDF'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Faktura (PDF)'
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
            'warehouseId' => null
        ]);
    }
}
