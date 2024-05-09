<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserDetailsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, ['label' => false,
                'attr'=>['placeholder' => 'First Name']])
            ->add('lastName', TextType::class, ['label' => false,
                'attr'=>['placeholder' => 'Last Name']])
            ->add('email', EmailType::class, ['label' => false,
                'attr'=>['placeholder' => 'Email']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Bind the form to the User entity
        ]);
    }
}
