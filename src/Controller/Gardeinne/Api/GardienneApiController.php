<?php

namespace App\Controller\Gardeinne\Api;

use App\Entity\Gardienne;
use App\Entity\Notification;
use App\Service\FcmNotification;
use App\Service\SerializeNotification;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/gardienne/api')]
class GardienneApiController extends AbstractController
{
    private $notificationRepository;
    private $manager ;
    private $translator;
    public function __construct(NotificationRepository $notificationRepository, ManagerRegistry $managerRegistry,TranslatorInterface $trans )
    {
        $this->notificationRepository = $notificationRepository;
        $this->manager = $managerRegistry->getManager();
        $this->translator = $trans;
    }
   
    #[Route('/onway', name:'onway_gardienne_api')]
    public function index()
    {
        /**@var Gardienne */
        $gardienne = $this->getUser();
        $salle = $gardienne->getSalles();
        //dd($salle,$this->notificationRepository->findSalleOnWay($salle));

        return $this->json(SerializeNotification::collectionToArray($this->notificationRepository->findSalleOnWay($salle)));
    }

   
    #[Route('/parking', name:'parking_gardienne_api')]
    public function inparking()
    {
        /**@var Gardienne */
        $gardienne = $this->getUser();
        $salle = $gardienne->getSalles();

        return $this->json(SerializeNotification::collectionToArray($this->notificationRepository->findSalleParking($salle)));
    }

    #[Route("/ready/{id}/{_locale<en|fr>}", defaults:['_locale'=>'en'], name:"gardienne_ready")]
    public function ready(Notification $notification = null): Response
    {
        /**@var Gardienne */
        $user = $this->getUser();
        if ($user->getSalles()->getId() == $notification->getSalle()->getId()) {
            # code...
            $notification->setIsReady(true);
            $this->manager->persist($notification);
            $this->manager->flush();
            FcmNotification::sendToTopic('Your child is ready',$notification->getEnfant()->getPrenom().' is ready','parent-'.$notification->getParent()->getId());
            return $this->json(['code'=>200,'message'=>$this->translator->trans('done')]);
        }
        return $this->json(['code'=>401,'message'=>$this->translator->trans('not allow')]);

    }

    #[Route("/close/{id}/{_locale<en|fr>}", defaults:['_locale'=>'en'],name:"gardienne_notif_close")]
    public function close(Notification $notification = null): Response
    {
         /**@var Gardienne */
         $user = $this->getUser();

        if ($user->getSalles()->getId() == $notification->getSalle()->getId()) {
            # code...
            $notification->setClose(true);
            $this->manager->persist($notification);
            $this->manager->flush();
            FcmNotification::sendToTopic('Enfant recuperer',$notification->getEnfant()->getPrenom().' a bien été recuperer. merci pour la confiance','parent-'.$notification->getParent()->getId());
            return $this->json(['code'=>200,'message'=>$this->translator->trans('done')]);
        }
        return $this->json(['code'=>401,'message'=>$this->translator->trans('not allow')]);

    }
}
