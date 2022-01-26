<?php

namespace App\Controller\Api\Parent;

use App\Entity\Enfant;
use App\Entity\Notification;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 *@Route("/api/parent")
 */
class ApiParentController extends AbstractController
{
    private $manager;
    private $notificationRepository;

    public function __construct(ManagerRegistry $doctrine,NotificationRepository $notif)
    {
        $this->manager = $doctrine->getManager();
        $this->notificationRepository = $notif;
    }
    /**
     *@Route("", name="api_parent", methods={"GET"})
     */
    public function index(): Response
    {
        $currentUser = $this->getUser();
        $user = [
            'code' => 200,
            'email' => $currentUser->getEmail(),
            'code_parent' => $currentUser->getCode(),
            'nom' => $currentUser->getNom(),
            'prenom' => $currentUser->getPrenom(),
            'telephone' => $currentUser->getTelephone(),
            'enfants' => $currentUser->getEnfants()
        ];
        return $this->json($user, 200, [], ['circular_reference_handler' => function ($object) {
            return $object->getId();
        }]);
    }
    /**
     *@Route("/notif/{id}",  name="notif_child", methods={"post"},)
     */
    public function notifie(Enfant $enfant = null,Request $request)
    {
        $minute = json_decode($request->getContent(),true);
        $user = $this->getUser();
        if ((!$user->getEnfants()->contains($enfant)) || $enfant==null) {
            # code...
            return $this->json(
                [
                    'code'=>500,
                    'message'=>'this child is not yours or does not exist'
                ]
            );
        }
        $notification = $this->notificationRepository->findParentToday($this->getUser(),$enfant);
        if (!$notification) {
            $notification = new Notification(); 
        }
        
        $notification->setWaiting(false);
        $notification->setParent($this->getUser());
        $notification->setEnfant($enfant);
        $notification->setEcole($enfant->getEcole());
        $notification->setSalle($enfant->getSalle());
        if (isset($minute['minute'])) {
            //creation d'une date de correspondante a celle de la soumisson du formulaire
            $datetime = new \DateTimeImmutable();
            //ajoutons de 1 pour des marge d'erreur
            $minute=$minute['minute']+1;
            //modifions la date et y ajoutans le temps en minute soumis
            $datetime = $datetime->modify("+$minute minutes");
            $notification->setTime($datetime);
            $this->manager->persist($notification);
            $this->manager->flush();
           // dd($notification,$minute);
           return $this->json([
               'code'=>200,
               'message'=>'notifi succes'
           ]);

        }


        return $this->json([
            'code'=>500,
            'message'=>'error on form'
        ]);
    }

    /**
     * @Route("/all_notif", name="notifi_child", methods={"GET"})
     */
    public function getAllTodayNotification()
    {
        $enfants = $this->getUser()->getEnfants();
        $notifications=[];

        foreach ($enfants as $enfant) {
            $notifications[] = $this->notificationRepository->findParentToday($this->getUser(),$enfant);
        }
        return $this->json([
            'code'=>200,
            'notifications'=>$notifications
        ], 200, [], ['circular_reference_handler' => function ($object) {
            return $object->getId();
        }]);
    }
}
