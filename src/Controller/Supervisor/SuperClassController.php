<?php

namespace App\Controller\Supervisor;

use App\Entity\Salle;
use App\Form\SalleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/super/class")
 */
class SuperClassController extends AbstractController
{
    private $em;

    public function __construct(ManagerRegistry $man)
    {
        $this->em = $man->getManager();
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
            return $this->redirectToRoute('super');
        }
        return $this->render('super_class/index.html.twig', [
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
       
        return $this->redirectToRoute('super');
    }
}
