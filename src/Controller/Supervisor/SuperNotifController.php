<?php

namespace App\Controller\Supervisor;

use App\Entity\Ecole;
use App\Entity\Notification;
use App\Service\FcmNotification;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/super/notif")]
class SuperNotifController extends AbstractController
{
    private $manager;
    public $translator;
    public function __construct( ManagerRegistry  $mangerRegistry, TranslatorInterface $trans ) {
        $this->manager = $mangerRegistry->getManager();
        $this->translator = $trans;

    }
 
    #[Route("/ready/{id}/{_locale<en|fr>}", defaults:['_locale'=>'en'], name:"super_notif")]
    public function index(Notification $notification = null): Response
    {
        /**@var Ecole */
        $user = $this->getUser();
        if (($user->getNotifications())->contains($notification)) {
            # code...
            $notification->setIsReady(true);
            $this->manager->persist($notification);
            $this->manager->flush();
            $fcm = FcmNotification::sendToTopic('Your child is ready',$notification->getEnfant()->getPrenom().' is ready','parent-'.$notification->getParent()->getId());
            return $this->json(['code'=>200,'message'=>$this->translator->trans('done')]);
        }
        return $this->json(['code'=>401,'message'=>$this->translator->trans('not allow')]);

    }

    #[Route("/close/{id}/{_locale<en|fr>}", defaults:['_locale'=>'en'],name:"super_notif_close")]
    public function close(Notification $notification = null): Response
    {
         /**@var Ecole */
         $user = $this->getUser();

        if ($user->getNotifications()->contains($notification)) {
            # code...
            $notification->setClose(true);
            $this->manager->persist($notification);
            $this->manager->flush();
            return $this->json(['code'=>200,'message'=>$this->translator->trans('done')]);
        }
        return $this->json(['code'=>401,'message'=>$this->translator->trans('not allow')]);

    }
}
