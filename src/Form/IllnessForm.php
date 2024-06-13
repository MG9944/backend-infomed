<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class IllnessForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The name field must not be blank',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'The given name is too long. It should have less than {{ limit }} characters.',
                    ]),
                    new Regex([
                        'pattern' => '/^[\s\p{L}]+$/u',
                    ]),
                ],
            ])
            ->add('category', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'The category field must not be blank',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'The given category is too long. It should have less than {{ limit }} characters.',
                    ]),
                    new Regex([
                        'pattern' => '/^[\s\p{L}]+$/u',
                    ]),
                ],
            ])
            ->add('medicamente', IntegerType::class, [
            ])
        ;
    }
}
