<?php

namespace App\Controller\Supervisor;

use App\Entity\Salle;
use App\Form\SalleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/super/class')]
class SuperClassController extends AbstractController
{
    private $em;

    public function __construct(ManagerRegistry $man)
    {
        $this->em = $man->getManager();
    }

    #[Route('/',  name:'index_super_class')]
    public function index(Request $request)
    {
        //debut de creation de salle
        $ecole = $this->getUser();
        $salle = new Salle();
        $salle->setEcole($ecole);
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->isValid()) {
                //dd($salle);
                $em =  $this->manager;
                $em->persist($salle);
                $em->flush();
                $this->addFlash('success', "Salle bien ajouter");
                return $this->redirectToRoute("index_super_class");
            } else {
                $this->addFlash('error', "errer");
            }
        }
        return $this->render('super_class/index.html.twig',[
            'salles' => $this->getUser()->getSalles(),
            'classForm'=>$form->createView()
        ]);
    }


    #[Route('/edite/{id}', name:'edite_super_class')]
    public function edite(Salle $salle,Request $request): Response
    {
        $form = $this->createForm(SalleType::class,$salle);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($salle);
            $this->em->flush();
            $this->addFlash('success',$salle->getNom().' was update');
            return $this->redirectToRoute('index_super_class');
        }
        return $this->render('super_class/edite.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name:'delete_super_class')]
    public function delete(Salle $salle=null): Response
    {
        if ($salle) {
         
            $this->em->remove($salle);
            $this->em->flush();
            $this->addFlash('success',$salle->getNom().' deleted');
        }
       
        return $this->redirectToRoute('index_super_class');
    }
}
