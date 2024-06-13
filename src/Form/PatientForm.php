<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class PatientForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pesel', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The pesel field must not be blank',
                    ]),
                    new Length([
                        'max' => 11,
                        'maxMessage' => 'The given firstname is too long. It should have less than {{ limit }} characters.',
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]+$/',
                    ]),
                ],
            ])
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
            ->add('address', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The address field must not be blank',
                    ]),
                    new Length([
                        'max' => 60,
                        'maxMessage' => 'The address provided is too long. It should have less than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('postCode', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The post code field must not be blank',
                    ]),
                    new Length([
                        'max' => 6,
                        'maxMessage' => 'The address provided is too long. It should have less than {{ limit }} characters.',
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]{2}-[0-9]{3}/m',
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The address field must not be blank',
                    ]),
                    new Length([
                        'max' => 60,
                        'maxMessage' => 'The address provided is too long. It should have less than {{ limit }} characters.',
                    ]),
                    new Regex([
                        'pattern' => '/^[\s\p{L}]+$/u',
                    ]),
                ],
            ])->add('phoneNumber', TextType::class, [
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
