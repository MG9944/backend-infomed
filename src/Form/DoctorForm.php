<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class DoctorForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The firstname field must not be blank',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'The given firstname is too long. It should have less than {{ limit }} characters.',
                    ]),
                    new Regex([
                        'pattern' => '/^[\s\p{L}]+$/u',
                    ]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The lastname field must not be blank',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'The given lastname is too long. It should have less than {{ limit }} characters.',
                    ]),
                    new Regex([
                        'pattern' => '/^[\s\p{L}]+$/u',
                    ]),
                ],
            ])
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
            ->add('password', PasswordType::class, [
                'trim' => true,
                'constraints' => [
                    new NotNull([
                        'message' => 'The password cannot be empty',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'The password provided is too short. It should have {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('phoneNumber', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The phone number field must not be blank',
                    ]),
                    new Length([
                        'max' => 12,
                        'maxMessage' => 'The given phone number is too long. It should have less than {{ limit }} characters.',
                    ]),
                    new Regex([
                        'pattern' => '/(?<!\w)(\(?(\+)?48\)?)?[ -]?\d{3}[ -]?\d{3}[ -]?\d{3}(?!\w)/m',
                    ]),
                ],
            ])
            ->add('medicalCenter', IntegerType::class, [
            ])
            ->add('specialisation', IntegerType::class, [
            ])
        ;
    }
}
