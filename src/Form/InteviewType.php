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
    private $date;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date of the Interview',
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')+ 1),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31),
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type of interview',
                'choices' => [
                    'Technical tests' => 'Technical tests',
                    'General Interview' => 'General Interview',
                    'Technical Interview' => 'Technical Interview'
                ]
            ])
            ->add('step', IntegerType::class, [
                'label' => "Step",
                'attr' => [
                    'min' => '1',
                    'max' => '10',
                ],
                'data' => 1

            ])
            ->add('done', ChoiceType::class, [
                'choices' => [
                    'No' => false,
                    'Yes' => true
                ],


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
