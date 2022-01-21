<?php

namespace App\Form;

use App\Entity\Gardienne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class GardienneEditeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('tel')
            ->add('salles')
            ->add('photo',FileType::class,[
                'mapped'=>false,
                'required'=>false,
                'constraints' =>[
                    new File([
                        'mimeTypes'=>['image/*']
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gardienne::class,
        ]);
    }
}
