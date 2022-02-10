<?php

namespace App\Controller\Supervisor;

use App\Entity\Notification;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/super/notif")
 */
class SuperNotifController extends AbstractController
{
    private $manager;
    public function __construct( ManagerRegistry  $mangerRegistry ) {
        $this->manager = $mangerRegistry->getManager();
    }
    /**
     * @Route("/ready/{id}", name="super_notif")
     */
    public function index(Notification $notification = null): Response
    {
        if ($this->getUser()->getNotifications()->contains($notification)) {
            # code...
            $notification->setIsReady(true);
            $this->manager->persist($notification);
            $this->manager->flush();
            return $this->json(['code'=>200,'message'=>'done']);
        }
        return $this->json(['code'=>401,'message'=>'not allow']);

    }

     /**
     * @Route("/close/{id}", name="super_notif_close")
     */
    public function close(Notification $notification = null): Response
    {
        if ($this->getUser()->getNotifications()->contains($notification)) {
            # code...
            $notification->setClose(true);
            $this->manager->persist($notification);
            $this->manager->flush();
            return $this->json(['code'=>200,'message'=>'done']);
        }
        return $this->json(['code'=>401,'message'=>'not allow']);

    }
}
