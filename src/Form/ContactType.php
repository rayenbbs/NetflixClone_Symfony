<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false, // Hide label
                'attr' => [
                    'placeholder' => 'Enter your name',
                    'class' => 'contactContainer-input', // Custom class for styling
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Enter your email address',
                    'class' => 'contactContainer-input', // Apply styling class
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Enter your message here...',
                    'class' => 'contactContainer-textarea', // Custom class for styling
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'contactContainer-submit', // Apply class for button styling
                    'value' => 'Send', // Placeholder for submit button
                ],
            ]);
    }
}
