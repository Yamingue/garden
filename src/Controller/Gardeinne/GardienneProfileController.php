<?php

namespace App\Controller\Gardeinne;

use App\Entity\Gardienne;
use App\Form\GardienneUpdateInfoType;
use App\Form\GardienneUpdatePhotoType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GardienneProfileController extends AbstractController
{
    private $manager;
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->manager = $managerRegistry->getManager();
    }
    #[Route('/gardienne/profile', name: 'app_gardienne_profile')]
    public function index(Request $request): Response
    {   /**@var Gardienne */
        $gardienne = $this->getUser();
        $photoform = $this->createForm(GardienneUpdatePhotoType::class);
        $photoform->handleRequest($request);
        if ($photoform->isSubmitted() && $photoform->isValid()) {
            
            /**@var File */
            $file = $photoform->get('photo')->getData();
            $fileName= uniqid().'.'.$file->guessExtension();
            $file->move('images/',$fileName);
            $gardienne->setPhoto('images/'.$fileName);
            $this->manager->persist($gardienne);
            $this->manager->flush();
            $this->addFlash('success','photo update');
            return $this->redirectToRoute('app_gardienne_profile');
        }

        $form= $this->createForm(GardienneUpdateInfoType::class,$gardienne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # code...
            $this->manager->persist($gardienne);
            $this->manager->flush();
            $this->addFlash('success','information update');
            return $this->redirectToRoute('app_gardienne_profile');
        }

        return $this->render('gardienne_profile/index.html.twig', [
            'controller_name' => 'GardienneProfileController',
            'photo'=>$photoform->createView(),
            'form'=>$form->createView(),
        ]);
    }
}
