<?php

namespace App\Form;

use App\Entity\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Application Date',
                'widget' => 'choice',
                'years' => range('2019', date('Y')+ 1),
                'months' => range(1, 12),
                'days' => range(1, 31),
            ])
            ->add('company_name', TextType::class, [
                'label' => 'Company Name'
            ])
            ->add('position_title', TextType::class, [
                'label' => 'Position title'
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'More Informations',
                'required' => false
            ])
            ->add('Save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
