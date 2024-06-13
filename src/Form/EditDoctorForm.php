<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class EditDoctorForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
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
            ->add('specialisation', IntegerType::class, [
            ])
        ;
    }
}
