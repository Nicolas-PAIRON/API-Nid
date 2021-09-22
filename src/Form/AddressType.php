<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address',null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max'=>120])
                ]
            ])
            ->add('town',null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max'=>58])
                ]
            ])
            ->add('postcode',null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max'=>8])
                ]
            ])
            ->add('country',null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max'=>33])
                ]
            ])
            //->add('user')
        ;
    }

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         'data_class' => AddressBill::class,
    //     ]);
    // }
}
