<?php

namespace App\Controller\Supervisor;

use App\Entity\Ecole;
use App\Entity\Enfant;
use App\Form\EnfantType;
use App\Form\EnfantEditType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/super/enfant')]
class EnfantController extends AbstractController
{
    private $manager;
    private $userRepo;
    private $translator;

    public function __construct(ManagerRegistry $doctrine, UserRepository $user, TranslatorInterface $translator)
    {
        $this->manager = $doctrine->getManager();
        $this->userRepo = $user;
        $this->translator = $translator;
    }
    #[Route('/{_locale<en|fr>}', name:'index_enfant', defaults:['_locale'=>'en'])]
    public function index(Request $request)
    {
        $ecole = $this->getUser();

       // ajout d'un enfant
       $enfant = new Enfant();
       $enfant->setEcole($ecole);
       $formEnfant = $this->createForm(EnfantType::class, $enfant);
       $formEnfant->handleRequest($request);
       if ($formEnfant->isSubmitted()) {
           if ($formEnfant->isValid()) {
               # code...
               $parents = $formEnfant->get("codeParent")->getData();
               $photo = $formEnfant->get('photo')->getData();
               $fileName = uniqid() . '.' . $photo->guessExtension();
               //dump($fileName);
               $photo->move('images/enfant', $fileName);
               $enfant->setPhoto('images/enfant/' . $fileName);
               $this->manager->persist($enfant);

               if ($parents) {
                   $parents = explode(',', $parents);
                   foreach ($parents as $code) {
                       $parent = $this->userRepo->findOneBy(['code' => $code]);
                       if ($parent) {
                           # code...
                           $parent->addEnfant($enfant);
                           $this->manager->persist($parent);
                       } else {
                           $this->addFlash("error",$code." ".$this->translator->trans(" not exite"));
                       }
                   }

                   # code...
               }
               $this->manager->flush();
               $this->addFlash('success', $enfant->getNom() .$this->translator->trans("Add"));
               return $this->redirectToRoute('index_enfant');
               //dump($enfant, explode(',', $parents), $parents);
           } else {
               $this->addFlash("error",$this->translator->trans("Form error"));
           }
       }

       return $this->render('super_enfant/index.html.twig',[
        'enfantForm'=>$formEnfant->createView()
       ]);
    }

    #[Route("/delete/{id}/{_locale<en|fr>}", name:'delete_enfant', defaults:['_locale'=>'en'])]
    public function delete(Enfant $enfant = null)
    {
        //Liste des gardienne de l'ecole
        $e_ecole = [];
        /**@var Ecole */
        $user = $this->getUser();
        //Convertion de l'array Collection en Array
        foreach ($user->getEnfants() as $e) {
            # code...
            $e_ecole[] = $e;
        }
        //test si la gardienne fait partie de l'ecole
        if (in_array($enfant, $e_ecole)) {
            $parents = $enfant->getParent();
            foreach ($parents as $p) {
                $p->removeEnfant($enfant);
                $enfant->removeParent($p);
                $this->manager->persist($p);
            }

            $this->manager->remove($enfant);
            $this->manager->flush();
            $this->addFlash('success', $this->translator->trans("Chid delete"));
        } else {
            $this->addFlash('error',$this->translator->trans("The Child is not part of this school"));
        }
        return $this->redirectToRoute("super");
    }

 
    #[Route("/edite/{id}/{_locale<en|fr>}", defaults:['_locale'=>'en'], name:'edite_enfant')]
    public function edite(Enfant $enfant, Request $request)
    {
        # code...
        $form = $this->createForm(EnfantEditType::class, $enfant);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                # recuperation de la photo
                $photo = $form->get('photo')->getData();
                if ($photo) {
                    # traitement de la photo
                    $fileName = uniqid() . '.' . $photo->guessExtension();
                    $photo->move('images/enfant', $fileName);
                    $enfant->setPhoto('images/enfant/' . $fileName);
                }

                $this->manager->persist($enfant);
                $parents = $form->get("codeParent")->getData();
                if ($parents) {
                    $parents = explode(',', $parents);
                    foreach ($parents as $code) {
                        $parent = $this->userRepo->findOneBy(['code' => $code]);
                        if ($parent) {
                            # code...
                            $parent->addEnfant($enfant);
                            $this->manager->persist($parent);
                        } else {
                            $this->addFlash("error",$code.$this->translator->trans("not exite"));
                        }
                    }

                    # code...
                }
                $this->manager->flush();
                $this->addFlash('success', $enfant->getNom() . $this->translator->trans("Edit successfully"));
                return $this->redirectToRoute('super');
                //dump($enfant, explode(',', $parents), $parents);
            } else {
                $this->addFlash('error', $this->translator->trans("Error in form"));
            }
        }

        return $this->render('super_enfant/edite.html.twig', [
            'form' => $form->createView(),
            'enfant' =>$enfant
        ]);
    }
}
