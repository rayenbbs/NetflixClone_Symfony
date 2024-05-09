<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, ['label' => false,
                'attr'=>['placeholder' => 'Old Password']])
            ->add('newPassword', PasswordType::class, ['label' => false,
                'attr'=>['placeholder' => 'New Password']])
            ->add('newPassword2', PasswordType::class, ['label' => false,
                'attr'=>['placeholder' => 'Confirm New Password']]);
    }
}
