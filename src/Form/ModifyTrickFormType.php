<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyTrickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr'  => [
                    'class' => 'form-control',
                    'style' => 'height: 4rem'
                ]
            ])
            ->add('group', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',
                'label' => false,
                'attr'  => [
                    'class' => 'form-control',
                    'style' => 'height: 2rem'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr'  => [
                    'class' => 'form-control',
                    'style' => 'height: 10rem'
                ]
            ])
            ->add('imageFiles', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false, // Not mapped to Trick entity
                'required' => false, // Allow empty uploads
                'attr' => [
                    'accept' => 'image/*',
                    'class'  => 'form-control hidden-row'
                ]
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
