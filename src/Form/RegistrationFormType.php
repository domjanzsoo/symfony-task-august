<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Enums\RankEnum;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'row_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'mt-1 w-full'
                ]
            ])
            ->add('email', TextType::class, [
                'row_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'mt-1 w-full'
                ]
            ])
            ->add('currentBase', TextType::class, [
                'row_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'mt-1 w-full'
                ]
            ])
            ->add('millitaryRank', ChoiceType::class, [
                'row_attr' => [
                    'class' => ''
                ],
                'attr' => [
                    'class' => 'mt-1 w-full'
                ],
                'choices' => RankEnum::toArray()
            ])
            ->add('password', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'invalid_message' => 'The password field must match',
                'attr' => [
                    'class' => 'w-full col-span-2 gap-2 grid grid-cols-2'
                ],
                'first_options' => [
                    'label' => 'Password',
                    'attr' => [
                        'class' => 'block w-full mt-1'
                    ],
                    'row_attr' => [
                        'class' => 'block',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ]
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'attr' => [
                        'class' => 'block w-full mt-1'
                    ],
                    'row_attr' => [
                        'class' => 'block',
                    ]
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
