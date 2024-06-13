<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class ForgotPasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The email field must not be blank',
                    ]),
                    new Email([
                        'message' => 'Email field does not contain a valid email address',
                    ]),
                    new Length([
                        'max' => 180,
                        'maxMessage' => 'The email provided is too long. It should have less than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('new_password', PasswordType::class, [
                'trim' => true,
                'constraints' => [
                    new NotNull([
                        'message' => 'The new password cannot be empty',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'The new password provided is too short. It should have {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('repeated_new_password', PasswordType::class, [
                'trim' => true,
                'constraints' => [
                    new NotNull([
                        'message' => 'The repeated password cannot be empty',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'The repeated new password is too short. It should have {{ limit }} characters',
                    ]),
                ],
            ]);
    }
}
