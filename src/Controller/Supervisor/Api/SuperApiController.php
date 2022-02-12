<?php

namespace App\Controller\Supervisor\Api;

use App\Entity\Ecole;
use App\Entity\Enfant;
use App\Repository\NotificationRepository;
use App\Service\SerializeNotification;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/super/api")
 */
class SuperApiController extends AbstractController
{
    private $notificationRepository;

    public function __construct(NotificationRepository $notif)
    {
        $this->notificationRepository = $notif;
    }
    /**
     * @Route("/salles", name="salle_super_api")
     */
    public function index(): Response
    {
        $salles = [];
        /**@var Ecole */
        $user = $this->getUser();
        foreach ($user->getSalles() as $salle) {
            # code...
            $salles[] = ["id"=> $salle->getId(),"nom"=>$salle->getNom()];
        }
        
        return $this->json($salles,200,[],['circular_reference_handler' => function ($object) {
            return $object->getId();
         }]);
    }

    /**
     * @Route("/notification", name="notification_super_api")
     */
    public function notification(): Response
    {
        /**@var Ecole */
       $userSuper = $this->getUser();
       $notifications = [];
       foreach ( $userSuper->getNotifications() as $notif) {
           # code...
           $notif->setEcole(null);
           $notif->setParent(null);
           $notif->setSalle(null);
           $notif->getEnfant()->setEcole(null);
           $notif->getEnfant()->setSalle(null);
           $notifications[] = $notif;
       }
        return $this->json($notifications,200,[],['circular_reference_handler' => function ($object) {
            return $object->getId();
         }]);
    }

    /**
     * @Route("/onway", name="onway_super_api")
     */
    public function onWay()
    {
        $ecole = $this->getUser();
       return $this->json(SerializeNotification::collectionToArray($this->notificationRepository->findEcoleOnWay($ecole)));
    }
    /**
     * @Route("/parking", name="parking_super_api")
     */
    public function parking()
    {
        $ecole = $this->getUser();
        return $this->json(SerializeNotification::collectionToArray($this->notificationRepository->findSuperParking($ecole)));
      
    }
}
