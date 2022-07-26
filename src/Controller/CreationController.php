<?php

namespace App\Controller;

use App\Entity\Domaine;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreationController extends AbstractController
{
    /**
     * @Route("/creation", name="index_creation")
     */
    public function index(ManagerRegistry $doctrine): Response 
    {
        $entityManager = $doctrine->getManager();

        $domaines = $entityManager->getRepository(Domaine::class)->findAll();

        return $this->render('creation/index.html.twig', [
            'domaines' => $domaines,
        ]);
    }

    /**
     * @Route("/creation/composants", name="mes_composants")
     */
    public function showMesComposants(): Response
    {
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
        $user = $this->getUser();
    
        $mesCompetences = $user->getCompetencesCreees();
    
        return $this->render('creation/components/mes_competences.html.twig', [
            'mes_competences' => $mesCompetences,
        ]);
    }
}
