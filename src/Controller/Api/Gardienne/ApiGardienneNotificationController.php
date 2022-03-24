<?php

namespace App\Controller\Api\Gardienne;

use App\Entity\Enfant;
use App\Entity\Gardienne;
use App\Entity\Notification;
use App\Service\SerializeNotification;
use App\Repository\NotificationRepository;
use App\Service\FcmNotification;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/gardienne')]
class ApiGardienneNotificationController extends AbstractController
{
    private $notificationRepository;
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }
    #[Route('/notification', name: 'api_gardienne_notification')]
    public function index()
    {
        /**@var Gardienne */
        $gardienne = $this->getUser();
        $salle = $gardienne->getSalles();

        return $this->json(SerializeNotification::collectionToArray($this->notificationRepository->findSalleOnWay($salle)));
    }

    #[Route('/parking', name: 'api_gardienne_parking')]
    public function inparking()
    {
        /**@var Gardienne */
        $gardienne = $this->getUser();
        $salle = $gardienne->getSalles();

        return $this->json(SerializeNotification::collectionToArray($this->notificationRepository->findSalleParking($salle)));
    }
    #[Route('/ready/{id}', name: 'api_gardienne_ready')]
    public function ready(Notification $notification = null, ManagerRegistry $managerRegistry)
    {
        /**@var Gardienne */
        $gardienne = $this->getUser();
        //dd($notification);
        $notifications = $gardienne->getSalles()->getNotifications();
        if ($notification != null && $notifications->contains($notification)) {
            $em = $managerRegistry->getManager();
            $notification->setIsReady(true);
            $em->persist($notification);
            $em->flush();
            FcmNotification::sendToTopic('Child ready',$notification->getEnfant()->getPrenom().' is ready','parent-'.$notification->getParent()->getId());
            return $this->json([
                'code' => 200,
                'message' => 'Done'
            ], 200);
        } else {
            return $this->json([
                'code' => 404,
                'message' => 'Invalide action'
            ]);
        }

        // return $this->json($this->getUser());
        // $salle = $gardienne->getSalles();

        //return $this->json(SerializeNotification::collectionToArray($this->notificatonRepository->findSalleParking($salle)));
    }
    #[Route('/close/{id}')]
    function close(Notification $notification = null,ManagerRegistry $managerRegistry)
    {
        //dd($notification);
        /**@var Gardienne */
        $gardienne = $this->getUser();
        //dd($notification);
        $notifications = $gardienne->getSalles()->getNotifications();
        if ($notification != null && $notifications->contains($notification)) {
            $em = $managerRegistry->getManager();
            $notification->setClose(true);
            $em->persist($notification);
            $em->flush();
            return $this->json([
                'code' => 200,
                'message' => 'Notification close'
            ], 200);
        } else {
            return $this->json([
                'code' => 404,
                'message' => 'Invalide action'
            ]);
        }

        dd($notification);
    }
}
