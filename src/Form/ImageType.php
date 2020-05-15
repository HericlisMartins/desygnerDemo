<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Url cannot be blank!']),
                ]
            ])
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Title cannot be blank!']),
                    new Length([
                        'min' => 1,
                        'max' => 30,
                        'minMessage' => 'Enter at least 1 character!',
                        'maxMessage' => 'You entered {{ value }} but you can not use more than {{ limit }} characters!',
                    ])
                ]
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Description cannot be blank!']),
                    new Length([
                        'min' => 1,
                        'max' => 30,
                        'minMessage' => 'Enter at least 1 character!',
                        'maxMessage' => 'You entered {{ value }} but you can not use more than {{ limit }} characters!',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'csrf_protection' => false,
        ]);
    }
}
