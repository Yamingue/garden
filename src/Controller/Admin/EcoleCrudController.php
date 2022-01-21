<?php

namespace App\Controller\Admin;

use App\Entity\Ecole;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EcoleCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Ecole::class;
    }


    
    public function configureFields(string $pageName): iterable
    {
        $name = uniqid();
        return [
            IdField::new('id')->onlyOnDetail(),
            TextField::new('name'),
            TextField::new('email'),
            // Field::new('password')
            //        ->setFormType(PasswordType::class),
            ImageField::new('logo')
                ->setBasePath('images/ecole/')
                ->setUploadDir('public/images/ecole/')
                ->setUploadedFileNamePattern("$name.[extension]")
        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setPassword('$2y$13$TsSWQdPwUdFplkMtxhVfcelvw53aH7SeQ3VLxKlBVkvw5J1Avf8gm');
        //dd($entityInstance);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

}
