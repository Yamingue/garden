<?php

namespace App\Controller\Supervisor;

use App\Entity\Ecole;
use App\Entity\Enfant;
use App\Entity\Gardienne;
use App\Entity\Salle;
use App\Form\EnfantType;
use App\Form\GardienneType;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/super")
 */
class SuperController extends AbstractController
{
    private $salleRepo;
    private $manager;
    private $userRepo;

    public function __construct(SalleRepository $salleRepo, ManagerRegistry $manager, UserRepository $user)
    {
        $this->salleRepo = $salleRepo;
        $this->manager = $manager->getManager();
        $this->userRepo = $user;
    }
    /**
     * @Route("/", name="super")
     */
    public function index(Request $request, Ecole $ecole = null): Response
    {

        if (!$ecole) {
            $ecole = $this->getUser();
            # code...
        }

        //debut de creation de salle
        $ecole = $this->getUser();
        $salle = new Salle();
        $salle->setEcole($ecole);
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->isValid()) {
                //dd($salle);
                $em =  $this->manager;
                $em->persist($salle);
                $em->flush();
                $this->addFlash('success', "Salle bien ajouter");
                return $this->redirectToRoute("super");
            } else {
                $this->addFlash('error', "errer");
            }
        }
        //fin de creation de salle
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
                $em->persist($gardienne);
                $em->persist($salles);
                $em->flush();

                # code...
                dump($gardienne);
                $this->addFlash('success', " Gardienne Ajouter");
                return $this->redirectToRoute('super');
            } else {
                $this->addFlash('error', "errer");
            }
        } // fin de creation de gardienne

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
                            $this->addFlash("error", "code $code not exite");
                        }
                    }

                    # code...
                }
                $this->manager->flush();
                $this->addFlash('success', $enfant->getNom() . " Ajouter");
                return $this->redirectToRoute('super');
                //dump($enfant, explode(',', $parents), $parents);
            } else {
                $this->addFlash("error", "erreur de formulaire");
            }
        }
        return $this->render('super/index.html.twig', [
            'classForm' => $form->createView(),
            'salles' => $ecole->getSalles(),
            'gardienFrom' => $gardienForm->createView(),
            'enfantForm' => $formEnfant->createView()
        ]);
    }
    /**
     * @Route("/onway", name="super_onway")
     */
    public function onway()
    {
        return $this->render('super/onway.html.twig');
    }
     /**
     * @Route("/parking", name="super_parking")
     */
    public function parking()
    {
        return $this->render('super/parking.html.twig');
    }
}
