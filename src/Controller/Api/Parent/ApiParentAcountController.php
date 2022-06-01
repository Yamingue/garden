<?php

namespace App\Controller\Api\Parent;

use App\Entity\User;
use App\Form\ApiUserUpdateType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/parent/acount')]
class ApiParentAcountController extends AbstractController
{
    private $hacher;
    private $manager;

    public function __construct(UserPasswordHasherInterface  $hacher, ManagerRegistry $manager)
    {
        $this->hacher = $hacher;
        $this->manager = $manager->getManager();
    }

    #[Route('/delete', name: 'app_parent_acount_delete')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'your deletion request is pending and will take effect within 30 days',
            'code' => 200,
        ]);
    }

    #[Route('/update', name: 'app_parent_acount_update', methods: ['POST'])]
    public function update(Request $request): Response
    {
        $content = json_decode($request->getContent(), true);
        /**@var User */
        $user = $this->getUser();
        //dd($content);
        $form = $this->createForm(ApiUserUpdateType::class, $user);
        // dd($form->createView());
        $form->submit($content);
        if (!$form->isValid()) {
            # code...
            $errors = [];
            foreach ($form->getErrors(true, true) as $error) {
                # code...
                $e = [
                    'label' => $error->getOrigin()->getName(),
                    'message' => $error->getMessage()
                ];
                $errors[] = $e;
            }
            //dd($errors);
            return $this->json([
                'error' => $errors,
                'code' => 200
            ]);
        }

        $this->manager->persist($user);
        $this->manager->flush();
        //$fon
        return $this->json([
            'message' => 'Acount update succes you need to login',
            'code' => 200,
        ]);
    }

    #[Route('/password/update', name: 'api_parent_pass_change', methods: ['POST'])]
    public function pass_change(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        if (isset($content['old']) && !empty($content['old']) && isset($content['new']) && !empty($content['new'])) {
            if ($this->hacher->isPasswordValid($this->getUser(), $content['old'])) {
                $hass = $this->hacher->hashPassword($this->getUser(), $content['new']);
                /**@var User */
                $user = $this->getUser();
                $user->setPassword($hass);

                $this->manager->persist($user);
                $this->manager->flush();

                return $this->json([
                    'message' => 'password update successfull',
                    'code' => 200,
                ]);
                # code...
            } else {
                return $this->json([
                    'message' => 'Invalide pass',
                    'code' => 401,
                ]);
            }
        } else {

            return $this->json([
                'message' => 'data can not be blank',
                'code' => 401,
            ]);
        }
        dd($content);
    }
}
