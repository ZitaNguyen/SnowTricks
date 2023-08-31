<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'height:4rem',
                    'placeholder' => 'Nom d\'utilisateur'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre nom d\'utilisateur.',
                    ])
                ]
            ])
            ->add('email', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'height:4rem',
                    'placeholder' => 'Email'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre email.',
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'height:4rem',
                    'placeholder' => 'Password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre mot de passe.',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Un mot de passe de minimum {{ limit }} caractères demandé.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ])
                ]
            ])
            ->add('image', FileType::class, [
                'label' => false,
                'required' => false, // Allow empty uploads
                'attr' => [
                    'class'  => 'form-control',
                    'accept' => 'image/*'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/*'],
                        'mimeTypesMessage' => 'Votre image n\'est pas valide.',
                    ])
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