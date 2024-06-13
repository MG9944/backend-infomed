<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class ChangePasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('old_password', PasswordType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The old password cannot be empty',
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
