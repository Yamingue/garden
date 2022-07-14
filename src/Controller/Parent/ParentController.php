<?php

namespace App\Controller\Parent;

use App\Entity\Enfant;
use DateTimeImmutable;
use App\Entity\Notification;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ParentController extends AbstractController
{
    private $manager;
    private $notificationRepository;

    public function __construct(ManagerRegistry $doctrine,NotificationRepository $notif)
    {
        $this->manager = $doctrine->getManager();
        $this->notificationRepository = $notif;
    }

    /**
     * @Route("/parent", name="parent")
     */
    public function index(): Response
    {
        return $this->render('parent/index.html.twig');
    }
    /**
     * @Route("/parent/noti/{id}", name="parent_notifie")
     */
    public function notifi(Enfant $enfant=null,Request $request): Response
    {
        
        if (!$this->getUser()->getEnfants()->contains($enfant)) {
            # code...
            return $this->redirectToRoute('parent');
        }
        $notification = $this->notificationRepository->findParentToday($this->getUser(),$enfant);
        if (!$notification) {
            $notification = new Notification(); 
        }
        $notification->setWaiting(false);
        $notification->setClose(false);
        $notification->setParent($this->getUser());
        $notification->setEnfant($enfant);
        $notification->setEcole($enfant->getEcole());
        $notification->setSalle($enfant->getSalle());
        $notification->setIsReady(false);
        //$form = $this->createForm(NotificationType::class,$notification);
        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('time',IntegerType::class,[
            'constraints'=>[
                new Range([
                    'min'=>0,
                    'max'=>120
                ])
            ]
        ]);
        $form = $formBuilder->getForm();
        $form->get('time')->setData($notification->getRestTime());
        //dump($notification);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //creation d'une date de correspondante a celle de la soumisson du formulaire
            $datetime = new DateTimeImmutable();
            //recuperation du temps en minute
            $minute = (int) $form->get('time')->getData();
            //ajoutons de 1 pour des marge d'erreur
            $minute+=1;
            //modifions la date et y ajoutans le temps en minute soumis
            $datetime = $datetime->modify("+$minute minutes");
            $notification->setTime($datetime);
            $this->manager->persist($notification);
            $this->manager->flush();
            $this->addFlash('success',"Notification envoyer");
            return $this->redirectToRoute('parent');
            dd($form->get('time')->getData());
        }


        return $this->render('parent/notifie.html.twig',[
            'enfant'=>$enfant,
            "form"=>$form->createView()
        ]);
    }
    /**
     * @Route("/parent/notif/parking/{id}", name="parent_parking")
     */
    public function inParking(Enfant $enfant=null){
        if (!$this->getUser()->getEnfants()->contains($enfant)) {
            # code...
            return $this->redirectToRoute('parent');
        }
        $notification = $this->notificationRepository->findParentToday($this->getUser(),$enfant);
        if (!$notification) {
            $this->addFlash('error',"there is not notification for ".$enfant->getNom());
            return $this->redirectToRoute('parent');
        }
        $notification->setWaiting(true);
        $notification->setClose(false);
        $this->manager->persist($notification);
        $this->manager->flush();
        $this->addFlash('success','Notifier');
        return $this->redirectToRoute("parent");
        //dd($notification);
    }
}
