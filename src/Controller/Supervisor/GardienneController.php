<?php

namespace App\Controller\Supervisor;

use App\Entity\Ecole;
use App\Entity\Gardienne;
use App\Form\GardienneType;
use App\Form\GardienneEditeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/super/gardienne')]
class GardienneController extends AbstractController
{
    private $manager;
    private $translator;

    public function __construct(ManagerRegistry $doctrine,TranslatorInterface $translator)
    {
        $this->manager = $doctrine->getManager();
        $this->translator =$translator;
    }

    #[Route("/{_locale<en|fr>}", defaults:['_locale'=>'en'], name:'index_gardienne')]
    public function index(Request $request, UserPasswordHasherInterface $hasher)
    {
        # code...
        $ecole = $this->getUser();
        $gardienne = new Gardienne();
        $gardienne->setEcole($ecole);

        //creation de gardienne
        $gardienForm = $this->createForm(GardienneType::class, $gardienne);
        $gardienForm->handleRequest($request);
        if ($gardienForm->isSubmitted()) {
            if ($gardienForm->isValid()) {
                # code...
                $photo = $gardienForm->get('photo')->getData();
                $fileName = uniqid() . '.' . $photo->guessExtension();
                //dump($fileName);
                $photo->move('images/gardienne', $fileName);
                $gardienne->setPhoto('images/gardienne/' . $fileName);

                $em = $this->manager;
                $salles = $gardienne->getSalles();
                $salles->addGardienne($gardienne);
                $gardienne->setPassword($hasher->hashPassword($gardienne,$gardienne->getPassword()));
                $em->persist($gardienne);
                $em->persist($salles);
                $em->flush();

                # code...
                dump($gardienne);
                $this->addFlash('success', $this->translator->trans("Babysitter Add"));
                return $this->redirectToRoute('index_gardienne');
            } else {
                $this->addFlash('error', $this->translator->trans("Error"));
            }
        } // fin de creation de gardienne


        return $this->render('super_gardienne/index.html.twig',[
            'gardienFrom' => $gardienForm->createView(),
        ]);
    }

    #[Route("/delete/{id}{_locale<en|fr>}", defaults:['_locale'=>'en'], name:'delete_gardienne')]
    public function delete(Gardienne $gardienne = null)
    {
        //Liste des gardienne de l'ecole
        $g_ecole = [];
        /**@var Ecole */
        $user = $this->getUser();
        //Convertion de l'array Collection en Array
        foreach ($user->getGardiennes() as $gar) {
            # code...
            $g_ecole[] = $gar;
        }
        //test si la gardienne fait partie de l'ecole
        if (in_array($gardienne, $g_ecole)) {
            $this->manager->remove($gardienne);
            $this->manager->flush();
            $this->addFlash('success',$this->translator->trans("Babysitter delete"));
        } else {
            $this->addFlash('error',$this->translator->trans("Babysitter is not part of this daycare"));
        }
        return $this->redirectToRoute("index_gardienne");
    }

    #[Route("/super/gardienne/edite/{id}/{_locale<en|fr>}", defaults:['_locale'=>'en'],name:'edite_gardienne')]
    public function edite(Gardienne $gardienne, Request $request)
    {
        # code...
        $form = $this->createForm(GardienneEditeType::class, $gardienne);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                # code...
                $photo = $form->get('photo')->getData();
                if ($photo) {
                    # code...
                    $fileName = uniqid() . '.' . $photo->guessExtension();
                    //dump($fileName);
                    $photo->move('images/gardienne', $fileName);
                    $gardienne->setPhoto('images/gardienne/' . $fileName);
                }
                # code...
                $this->manager->persist($gardienne);
                $this->manager->flush();
                $this->addFlash('success', "'$gardienne'".$this->translator->trans("Edite"));
                return $this->redirectToRoute('index_gardienne');
            }else{
                $this->addFlash('error',$this->translator->trans("Error in form"));
            }
        }

        return $this->render('super_gardienne/edite.html.twig', [
            'form' => $form->createView(),
            'name' => $gardienne
        ]);
    }
}
