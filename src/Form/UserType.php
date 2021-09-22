<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new Length(['max'=>180])
                ]
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false, 'constraints'=>[new NotBlank()]])

            ->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $formEvent) {
                $form = $formEvent->getForm();
                $user = $formEvent->getData();
                    
                if(!is_null($user->getId())){
                    $form->add('firstname', TextType::class, [
                    'constraints' => [
                        //new NotBlank(),
                        new Length(['max'=>50]),
                            ]
                        ])
                        ->add('lastname', TextType::class, [
                            'constraints' => [
                                //new NotBlank(),
                                new Length(['max'=>50]),
                            ]
                        ])
                        ->add('phoneNumber', TelType::class, [
                            'constraints' => [
                                //new NotBlank(),
                                new Length(['max'=>15]),
                                new Regex(['pattern'=>'/\D/','match' => false, 'message' => 'Your phone number must contain just numbers'])
                            ]
                        ]) ;
                }
            })
            //->add('roles')
            //->add('createdAt')
            //->add('updatedAt')
            //->add('product')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
