<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AddTrickFormType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr'  => [
                    'class' => 'form-control',
                    'style' => 'height: 4rem',
                    'placeholder' => 'Nom du figure'
                ]
            ])
            ->add('group_id', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',
                'label' => false,
                'placeholder' => 'Choisissez un catégorie',
                'attr'  => [
                    'class' => 'form-control',
                    'style' => 'height: 4rem',
                ],
                // 'choice_attr' => function ($value) {
                //     if ($value === null) {
                //         return ['class' => 'text-muted'];
                //     }
                //     return [];
                // }
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr'  => [
                    'class' => 'form-control',
                    'style' => 'height: 10rem',
                    'placeholder' => 'Description'
                ]
            ])
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false, // Not mapped to Trick entity
                'required' => false, // Allow empty uploads
                'attr' => [
                    'accept' => 'image/*',
                    'class'  => 'form-control'

                ],
                // 'constraints' => [
                //     new File([
                //         'maxSize' => '1024k',
                //         'mimeTypes' => ['image/*'],
                //         'mimeTypesMessage' => 'Please upload a valid image file',
                //     ])
                // ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
