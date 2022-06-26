<?php

namespace App\Controller\Supervisor;

use App\Entity\Ecole;
use App\Entity\Salle;
use App\Form\SalleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/super/class')]
class SuperClassController extends AbstractController
{
    private $em;
    private $translator;
    public function __construct(ManagerRegistry $man, TranslatorInterface $translator)
    {
        $this->em = $man->getManager();
        $this->translator = $translator;
      
    }

    #[Route("/{_locale<en|fr>}", defaults:['_locale'=>'en'],  name:'index_super_class')]
    public function index(Request $request)
    {
        //debut de creation de salle

        /**@var Ecole */
        $ecole = $this->getUser();;
        $salle = new Salle();
        $salle->setEcole($ecole);
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->isValid()) {
                //dd($salle);
                $em =  $this->em;
                $em->persist($salle);
                $em->flush();
                $this->addFlash('success',$this->translator->trans("Room well add"));
                return $this->redirectToRoute("index_super_class");
            } else {
                $this->addFlash('error', $this->translator->trans("Errer"));
            }
        }
        return $this->render('super_class/index.html.twig',[
            'salles' => $ecole->getSalles(),
            'classForm'=>$form->createView()
        ]);
    }


    #[Route("/edite/{id}/{_locale<en|fr>}", defaults:['_locale'=>'en'], name:'edite_super_class')]
    public function edite(Salle $salle,Request $request): Response
    {
        $form = $this->createForm(SalleType::class,$salle);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($salle);
            $this->em->flush();
            $this->addFlash('success',$salle->getNom().' '.$this->translator->trans('was update'));
            return $this->redirectToRoute('index_super_class');
        }
        return $this->render('super_class/edite.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/delete/{id}/{_locale<en|fr>}", defaults:['_locale'=>'en'], name:'delete_super_class')]
    public function delete(Salle $salle=null): Response
    {
        if ($salle) {
         
            $this->em->remove($salle);
            $this->em->flush();
            $this->addFlash('success',$salle->getNom().' '.$this->translator->trans('deleted'));
        }
       
        return $this->redirectToRoute('index_super_class');
    }
}
