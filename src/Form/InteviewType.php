<?php

namespace App\Form;

use App\Entity\Interview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InteviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date of the Interview'
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type of interview',
                'choice' => [
                    'Technical tests' => 'Technical tests',
                    'General Interview' => 'General Interview',
                    'Technical Interview' => 'Technical Interview'
                ]
            ])
            ->add('step', IntegerType::class, [
                'label' => "Step"
            ])
            ->add('done', ChoiceType::class, [
                'Yes' => true,
                'No' => false
            ])
            ->add('Save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Interview::class,
        ]);
    }
}
