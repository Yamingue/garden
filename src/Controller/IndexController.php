<?php

namespace App\Controller;

use App\Entity\ContactUs;
use App\Form\ContactType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ManagerRegistry $registry): Response
    {
        $contact = new ContactUs();
        $form = $this->createForm(ContactType::class,$contact);

        if ($form->isSubmitted() && $form->isValid()) {
            # code...
            $em = $registry->getManager();
            $em->persist($contact);
            $em->flush();
            $this->addFlash('success', 'message send');
        }


        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/home", name="home")
     */
    public function home(): Response
    {
       return $this->redirectToRoute('index');
    }
     /**
     * @Route("/index", name="acceuille")
     */
    public function home1(): Response
    {
        return $this->redirectToRoute('index');
    }
}
