<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserApiType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiRegistrationController extends AbstractController
{
    #[Route('/api/registration', name: 'app_api_registration', methods:['POST'])]
    public function index(Request $request,ManagerRegistry $registry, UserPasswordHasherInterface $hasher ): Response
    {
        $content = json_decode($request->getContent(),true);
        $user = new User();
        //dd($content);
        $form = $this->createForm(UserApiType::class,$user);
       // dd($form->createView());
        $form->submit($content);
        if (!$form->isValid()) {
            # code...
            $errors = [];
            foreach ($form->getErrors(true,true) as $error) {
                # code...
                $e= [
                    'label'=>$error->getOrigin()->getName(),
                    'message'=>$error->getMessage()
                ];
                $errors[]= $e;
            }
            //dd($errors);
            return $this->json([
                'error'=>$errors,
                'code'=>200
            ]);
        }
        $user->setCode(uniqid());
        $user->setPassword($hasher->hashPassword($user,$user->getPassword()));
        $em = $registry->getManager();
        $em->persist($user);
        $em->flush();
        //$fon
        return $this->json([
            'message' => 'User create success full',
            'code' => 200,
        ]);
    }
}
