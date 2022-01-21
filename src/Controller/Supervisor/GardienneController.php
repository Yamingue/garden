<?php

namespace App\Controller\Supervisor;

use App\Entity\Gardienne;
use App\Form\GardienneEditeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class GardienneController extends AbstractController
{
    private $manager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->manager = $doctrine->getManager();
    }

    /**
     * @Route("/super/gardienne/delete/{id}", name="delete_gardienne")
     */
    public function delete(Gardienne $gardienne = null)
    {
        //Liste des gardienne de l'ecole
        $g_ecole = [];

        //Convertion de l'array Collection en Array
        foreach ($this->getUser()->getGardiennes() as $gar) {
            # code...
            $g_ecole[] = $gar;
        }
        //test si la gardienne fait partie de l'ecole
        if (in_array($gardienne, $g_ecole)) {
            $this->manager->remove($gardienne);
            $this->manager->flush();
            $this->addFlash('success', "Gardienne suprimer");
        } else {
            $this->addFlash('error', "Gardienne ne fait n'est pas de l'ecole");
        }
        return $this->redirectToRoute("super");
    }

    /**
     * @Route("/super/gardienne/edite/{id}", name="edite_gardienne")
     */
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
                $this->addFlash('success', "Garienne " . $gardienne . " modifier");
                return $this->redirectToRoute('super');
            }else{
                $this->addFlash('error', "error in form ");
            }
        }

        return $this->render('form.html.twig', [
            'form' => $form->createView(),
            'name' => $gardienne
        ]);
    }
}
