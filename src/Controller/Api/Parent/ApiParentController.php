<?php

namespace App\Controller\Api\Parent;

use App\Entity\User;
use App\Entity\Enfant;
use App\Entity\Notification;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\NotificationRepository;
use App\Service\FcmNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/parent')]
class ApiParentController extends AbstractController
{
    private $manager;
    private $notificationRepository;

    public function __construct(ManagerRegistry $doctrine,NotificationRepository $notif)
    {
        $this->manager = $doctrine->getManager();
        $this->notificationRepository = $notif;
    }

    #[Route('/fcmtoken', name:'user_fcmToken', methods:['POST'])]
    public function tokenRefresh(Request $req){
        $content = json_decode($req->getContent(),true);
        /**@var User */
        $user = $this->getUser();

        if (isset($content['fcmtoken']) && $content['fcmtoken'] != null ) {
            # code...
            $user->setFcmtoken($content['fcmtoken']);
            $this->manager->persist($user);
            $this->manager->flush();

            return $this->json([
                'code'=>200,
                'message'=>'token update'
            ]);

        }
        return $this->json([
            'code'=>403,
            'message'=>'Something wrong'
        ]);
    }

    #[Route('/', name:'api_parent', methods:['GET'])]
    public function index()
    {
        /**@var User */
        $currentUser = $this->getUser();
        $enfants =[];
        foreach ($currentUser->getEnfants() as $e) {
            # code...
            $enfant =[
                'id'=>$e->getId(),
                'photo'=>$e->getPhoto(),
                'nom'=>$e->getNom(),
                'prenom'=>$e->getPrenom(),
                'ecole'=>[
                    'nom'=>$e->getEcole()->getName()
                ]
            ];
            $enfants[] = $enfant;
        }
       
        $user = [
            'code' => 200, 
            'id'=>$currentUser->getId(),
            'email' => $currentUser->getEmail(),
            'code_parent' => $currentUser->getCode(),
            'nom' => $currentUser->getNom(),
            'prenom' => $currentUser->getPrenom(),
            'telephone' => $currentUser->getTelephone(),
            'enfants' => $enfants
        ];
        return $this->json($user);
    }

    #[Route('/notif/{id}', name:'notif_parent', methods:['POST'])]
    public function notifie(Enfant $enfant = null,Request $request)
    {
        $minute = json_decode($request->getContent(),true);
        /**@var User */
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
        $notification->setIsReady(false);
        $notification->setClose(false);
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
           $classChild = $enfant->getSalle()->getId();
           $fcmResponse = FcmNotification::sendToTopic($enfant->getNom().' is comming',$enfant->getNom().' parent comme in '.$minute.' mn','class-'.$classChild);
           return $this->json([
               'code'=>200,
               'message'=>'notifi succes',
               'fcm'=>$fcmResponse
           ]);

        }


        return $this->json([
            'code'=>500,
            'message'=>'error on form'
        ]);
    }

    #[Route('/all_notif', name:'notifi_all_child', methods:['POST'])]
    public function getAllTodayNotification()
    {
        /**@var User */
        $user = $this->getUser();
        $enfants = $user->getEnfants();
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

    #[Route('/parking/{id}', name:'inparking', methods:['GET'])]
    public function inParking(Enfant $enfant=null){
        /**@var User */
        $user = $this->getUser();
        if ($enfant == null) {
            # code...
            return $this->json([
                'code'=>404,
                'message'=>'chil not selected'
            ]);
        }
        if (!$user->getEnfants()->contains($enfant)) {
            # code...
            return $this->json([
                'code'=>404,
                'message'=>'this childrenn is not yours'
            ]);
        }
        $notification = $this->notificationRepository->findParentToday($this->getUser(),$enfant);
        if (!$notification) {
           // $this->addFlash('error',"there is not notification for ".$enfant->getNom());
            return $this->json([
                'code'=>404,
                'message'=>'there is not notification for select child'
            ]);
           // return $this->redirectToRoute('parent');
        }
        if ($notification->getClose()) {
            # code...
            $notification->setClose(false);
            $notification->setIsReady(false);
        }
        $notification->setWaiting(true);
        $this->manager->persist($notification);
        $this->manager->flush();
        $classChild = $enfant->getSalle()->getId();
        $fcmResponse = FcmNotification::sendToTopic($enfant->getNom()."'s parent is here",$enfant->getNom().' must be ready to go','class-'.$classChild);
        
        return $this->json([
            'code'=>200,
            'message' =>'notifie',
            'fcm'=>$fcmResponse
        ],200);
       // dd($notification);
    }

    #[Route("/isready/{id}")]
    public function childReady(Enfant $enfant)
    {
        /**@var User */
        $currentUser = $this->getUser();
        if ($currentUser->getEnfants()->contains($enfant)) {
            $notification = $this->notificationRepository->findParentToday($this->getUser(),$enfant);
            //dd($notification);
            if ($notification!=null && !$notification->getClose()) {
                return $this->json(['code'=>200,'ready'=>$notification->getIsReady()]);   
            }
        }
        return $this->json(['code'=>404,'ready'=>false]);
    }
}
