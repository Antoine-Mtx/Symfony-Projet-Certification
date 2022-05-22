<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DomaineController extends AbstractController
{
    /**
     * @Route("/domaine", name="index_domaine")
     */
    public function index(ManagerRegistry $doctrine): Response// on injecte le service Doctrine dans la méthode index de notre controller, ce qui nous permettra d'interagir avec notre base de données
    {
        $domaines = $doctrine->getRepository(Domaine::class)->findAll(); // on désigne le repository de la classe "Domaine" à notre gestionnaire $doctrine puis on utilise la méthode findAll() pour récupérer toutes les instances de cette classe

        return $this->render('domaine/index.html.twig', [
            'domaines' => $domaines,
        ]);
    }

    
}
