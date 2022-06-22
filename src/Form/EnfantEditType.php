<?php

namespace App\Form;

use App\Entity\Enfant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EnfantEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('photo',FileType::class,[
            'constraints'=>[
                new File([
                    'mimeTypes'=>'image/*'
                ]),
            ],
            'mapped' => false,
            "required" => false
        ])
        ->add('nom')
        ->add('prenom')
        ->add('age')
        ->add('salle')
        ->add('codeParent',TextType::class,[
            'mapped'=>false,
            'required'=>false,
            'label'=>"parents codes (separated by comma if you have many)"
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enfant::class,
        ]);
    }
}
