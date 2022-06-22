<?php

namespace App\Controller\Supervisor;


use App\Entity\Ecole;
use App\Form\SuperChangeInfoType;
use App\Form\SuperChangePassType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\EqualTo;

#[Route('/super')]
class SuperController extends AbstractController
{
   
    private $hasher;
    private $manager;
    public function __construct(UserPasswordHasherInterface $hasher,ManagerRegistry $managerRegistry)
    {
        $this->hasher = $hasher;
        $this->manager = $managerRegistry->getManager();
    
    }

    #[Route('/', name: 'super')]
    public function index(): Response
    {

        $ecole = $this->getUser();

        return $this->render('super/index.html.twig', [
            'salles' => $ecole->getSalles(),
        ]);
    }

    #[Route('/onway', name: 'super_onway')]
    public function onway()
    {
        return $this->render('super/onway.html.twig');
    }

    #[Route('/parking', name: 'super_parking')]
    public function parking()
    {
        return $this->render('super/parking.html.twig');
    }


    #[Route('/profile', name: 'super_profile')]
    public function profile(Request $request)
    {
        /**@var Ecole */
        $ecole = $this->getUser();
        
        $form = $this->createForm(SuperChangeInfoType::class,$ecole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var UploadedFile */
            $file = $form->get('logo')->getData();
            if ($file) {
                # code...
                $name = uniqid().'.'.$file->guessClientExtension();
                $file->move('images/ecole',$name);
                $ecole->setLogo($name);
            }
            $this->manager->persist($ecole);
            $this->manager->flush();
            $this->addFlash('success','update done');
            return $this->redirectToRoute('super_profile');
        }

       
        $passform =$this->createForm(SuperChangePassType::class);
        $passform->handleRequest($request);
        if ($passform->isSubmitted() && $passform->isValid()) {
            # code...
            $old= $passform->get('old_password')->getData();
            $new= $passform->get('password')->getData();
            $v = $this->hasher->isPasswordValid($ecole,$old);
            if($v){
               // $ecole
                $ecole->setPassword($this->hasher->hashPassword($ecole,$new));
                $this->manager->persist($ecole);
                $this->manager->flush($ecole);
                $this->addFlash('success','Password update');
                return $this->redirectToRoute('super_profile');
            }else{
                $this->addFlash('error','Password don\'t match');
            }
        }
        return $this->render('super/profile.html.twig', [
            'form' => $form->createView(),
            'passform'=>$passform->createView()
        ]);
    }
}
