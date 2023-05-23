<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LoanFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('loanAmount', RangeType::class, [
                'label' => 'Loan Amount (PLN)',
                'attr' => [
                    'min' => 1000,
                    'max' => 20000,
                    'step' => 25,
                    'class' => 'block w-full',
                ],
            ])
            ->add('loanTerm', ChoiceType::class, [
                'label' => 'Loan Term (Months):',
                'required' => true,
                'choices' => [
                    '3' => 3,
                    '6' => 6,
                    '9' => 9,
                    '12' => 12,
                    '24' => 24,
                ],
                'expanded' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Calculate',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}