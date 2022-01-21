<?php

namespace App\Controller\Supervisor;

use App\Form\SalleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/super/class")
 */
class SuperClassController extends AbstractController
{
    /**
     * @Route("/new", name="new_super_class")
     */
    public function index(Request $request): Response
    {
        $from = $this->createForm(SalleType::class);
        return $this->render('super_class/index.html.twig', [
            'form' => $from->createView(),
        ]);
    }
}
