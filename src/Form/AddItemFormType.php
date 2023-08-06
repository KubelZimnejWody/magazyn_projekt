<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
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
            ->add("name", TextType::class, [
                "label" => false,
                "required" => true,
                "attr" => [
                    "minlength" => 3,
                    "placeholder" => "Nazwa"
                ]
            ])
            ->add("unit", TextType::class, [
                "label" => false,
                "required" => true,
                "attr" => [
                    "minlength" => 1,
                    "placeholder" => "Jednostka"
                ]
            ])
            ->add("vat", NumberType::class, [
                "label" => false,
                "required" => true,
                "attr" => [
                    "min" => 1,
                    "placeholder" => "Ilość VAT"
                ]
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Zatwierdź",
                "attr" => [
                    "class" => "btn btn-primary w-100"
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
