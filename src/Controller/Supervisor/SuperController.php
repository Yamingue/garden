<?php

namespace App\Controller\Supervisor;

use App\Entity\Ecole;
use App\Entity\Salle;
use App\Entity\Enfant;
use App\Form\SalleType;
use App\Form\EnfantType;
use App\Entity\Gardienne;
use App\Form\GardienneType;
use App\Repository\UserRepository;
use App\Repository\SalleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
    public function index(): Response
    {

        $ecole = $this->getUser();
           
        return $this->render('super/index.html.twig', [
            'salles' => $ecole->getSalles(),
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
