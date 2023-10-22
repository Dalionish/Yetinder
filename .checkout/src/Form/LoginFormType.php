<?php

namespace App\Form;

use App\Validator\ExistingEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(),
                    new ExistingEmail(),
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Heslo',
                'constraints' => [
                    new NotBlank()
                ]
            ]);
    }
}