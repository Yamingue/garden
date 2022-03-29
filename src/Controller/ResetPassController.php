<?php

namespace App\Controller;

use App\Repository\GardienneRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/reset/pass')]
class ResetPassController extends AbstractController
{
    private $em;
    private $hasher;
    private $mailer;
    function __construct(ManagerRegistry $mr, UserPasswordHasherInterface $hasher, MailerInterface $mailer)
    {
        $this->em= $mr->getManager();
        $this->hasher= $hasher;
        $this->mailer = $mailer;
    }

    #[Route('/gardienne', name: 'app_reset_pass_gardienne', methods:['POST'])]
    public function index(Request $request,GardienneRepository $gr): Response
    {
        $email = json_decode($request->getContent(),true);
        if (isset($email['email']) && !empty($email['email'])) {
            # code...
            $gardienne = $gr->findOneBy(['email'=>$email]);
            if ($gardienne) {
                # code...
                $pass = uniqid();
                $gardienne->setPassword($this->hasher->hashPassword($gardienne,$pass));
                $this->em->persist($gardienne);
                $this->em->flush();
                $email = (new Email())
                        ->from('admin@garden.umoyatech.ca')
                        ->to($email['email'])
                        ->subject('you have request new password')
                        ->text("$pass is your new password");
                $this->mailer->send($email);
                return $this->json([
                    'code'=>200,
                    'message'=>'new passworld has been send to your mail'
                ]);
                
            }

        }
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ResetPassController.php',
        ]);
    }
}
