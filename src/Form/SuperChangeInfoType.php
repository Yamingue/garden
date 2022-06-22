<?php

namespace App\Form;

use App\Entity\Ecole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SuperChangeInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('logo', FileType::class, [
                'mapped' => false,
                "required" => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1M'
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class,)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ecole::class,
        ]);
    }
}
