<?php

namespace App\Controller\Supervisor\Api;

use App\Repository\NotificationRepository;
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
        foreach ($this->getUser()->getSalles() as $salle) {
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
       
        return $this->json($this->getUser()->getNotifications(),200,[],['circular_reference_handler' => function ($object) {
            return $object->getId();
         }]);
    }

    /**
     * @Route("/onway", name="onway_super_api")
     */
    public function onWay()
    {
        $ecole = $this->getUser();

        return $this->json($this->notificationRepository->findEcoleOnWay($ecole),200,[],['circular_reference_handler' => function ($object) {
            return $object->getId();
         }]);
    }
    /**
     * @Route("/parking", name="parking_super_api")
     */
    public function parking()
    {
        $ecole = $this->getUser();

        return $this->json($this->notificationRepository->findSuperParking($ecole),200,[],['circular_reference_handler' => function ($object) {
            return $object->getId();
         }]);
    }
}
