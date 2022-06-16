<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Domaine;
use App\Entity\Competence;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $domaines = $doctrine->getRepository(Domaine::class)->findAll(); // on désigne le repository de la classe "Domaine" à notre gestionnaire $doctrine puis on utilise la méthode findAll() pour récupérer toutes les instances de cette classe
        $utilisateurs = $doctrine->getRepository(User::class)->findAll();
        $competences = $doctrine->getRepository(Competence::class)->findAll();

        return $this->render('home/index.html.twig', [
            'domaines' => $domaines,
            'utilisateurs' => $utilisateurs,
            'competences' => $competences,
        ]);
    }
}
