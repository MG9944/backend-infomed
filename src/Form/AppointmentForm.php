<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class AppointmentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('patient', IntegerType::class, [
            ])
            ->add('appointmentDate', DateTimeType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotNull([
                        'message' => 'The date field must not be blank',
                    ]),
                ],
            ])
            ->add('diagnose', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The diagnosis field must not be blank',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'The given diagnosis is too long. It should have less than {{ limit }} characters.',
                    ]),
                ],
            ])
        ;
    }
}
