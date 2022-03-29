<?php

namespace App\Controller\Api\Gardienne;

use App\Entity\Gardienne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
#[Route('/api/gardienne/acount', methods:['POST'])]
class ApiGardienneAcountController extends AbstractController
{
    #[Route('/update_pass', name: 'app_api_gardienne_acount')]
    public function index( Request $request, UserPasswordHasherInterface $hasser, ManagerRegistry $registry ): Response
    {
        
        $content = json_decode($request->getContent(),true);
        if (isset($content['oldpass'],$content['newpass']) && !empty($content['oldpass']) && !empty($content['newpass']) ) {
         
            /**@var Gardienne */
            $user =$this->getUser();
            if ($hasser->isPasswordValid($user,$content['oldpass'])) {
                # code...
                $em = $registry->getManager();
                $user->setPassword($hasser->hashPassword($user,$content['newpass']));
                $em->persist($user);
                $em->flush();
                return $this->json([
                    'code'=>200,
                    'message'=>'Password update'
                ]);
            }
            return $this->json([
                'code'=>403,
                'message'=>'Something wrong'
            ]);
        }
        return $this->json([
            'message' => 'data can not be empty',
            'code' => 200,
        ]);
    }
}
