<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreationController extends AbstractController
{
    /**
     * @Route("/creation", name="index_creation")
     */
    public function index(): Response
    {
        return $this->render('creation/index.html.twig', [
            'controller_name' => 'CreationController',
        ]);
    }

    /**
     * @Route("/creation/composants", name="mes_composants")
     */
    public function showMesComposants(): Response
    {
        // $entityManager = $doctrine->getManager();

        $user = $this->getUser();

        $mesComposants = $user->getComposantsCrees();

        return $this->render('creation/components/mes_composants.html.twig', [
            'mes_composants' => $mesComposants,
        ]);
    }

    /**
     * @Route("/creation/competences", name="mes_competences")
     */
    public function showMesCompetences(): Response
    {
        // $entityManager = $doctrine->getManager();
    
        $user = $this->getUser();
    
        $mesCompetences = $user->getCompetencesCreees();
    
        return $this->render('creation/components/mes_competences.html.twig', [
            'mes_competences' => $mesCompetences,
        ]);
    }
}
