<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class EditAppointmentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('temperature', NumberType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The temperature field must not be blank',
                    ]),
                ],
            ])
            ->add('bloodPressure', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The blood pressure field must not be blank',
                    ]),
                    new Length([
                        'max' => 7,
                        'maxMessage' => 'The given blood pressure is too long. It should have less than {{ limit }} characters.',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{1,3}\\/\d{1,3}$/',
                    ]),
                ],
            ])
            ->add('sugarLevel', IntegerType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The sugar level field must not be blank',
                    ]),
                ],
            ])
            ->add('medicamenteDescription', TextType::class, [
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The description field must not be blank',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'The given description is too long. It should have less than {{ limit }} characters.',
                    ]),
                    new Regex([
                        'pattern' => '/^[\s\p{L}]+$/u',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
