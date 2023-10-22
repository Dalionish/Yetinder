<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\UniqueValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new UniqueValue(),
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Heslo',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Heslo musí mít minimálně 5 znaků!',
                    ]),
                ]
            ])
            ->add('password2', PasswordType::class, [
                'label' => 'Heslo znovu',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Heslo musí mít minimálně 5 znaků!',
                    ]),
                    new Callback([
                        'callback' => [$this, 'validatePasswords'],
                    ]),
                ],
            ]);

    }

    public function validatePasswords($data, ExecutionContextInterface $context)
    {
        $form = $context->getRoot();
        $password = $form->get('password')->getData();
        $confirmPassword = $form->get('password2')->getData();

        if ($password !== $confirmPassword) {
            $context->buildViolation('Hesla se musí shodovat!')
                ->atPath('password2')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}