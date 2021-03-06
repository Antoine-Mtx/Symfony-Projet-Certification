<?php

namespace App\Controller;

use DateTime;
use App\Entity\Competence;
use App\Entity\Apprentissage;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ApprentissageController extends AbstractController
{
    /**
     * @Route("/apprentissage", name="index_apprentissage")
     */
    public function index(): Response
    {
        return $this->render('profile/components/competences_suivies.html.twig', [
            'controller_name' => 'ApprentissageController',
        ]);
    }

    /**
     * @Route("/apprentissage/new/{competence_id}}", name="new_apprentissage")
     * @ParamConverter("competence", options={"mapping": {"competence_id": "id"}})
     */
    public function new(ManagerRegistry $doctrine, Competence $competence): Response
    {
        $apprenant = $this->getUser();

        if (! in_array($competence, $apprenant->getCompetencesSuivies())) {
            $apprentissage = new Apprentissage();
            $aujourdhui = new DateTime();            
            $entityManager = $doctrine->getManager();
                
            $apprentissage->setApprenant($apprenant);
            $apprentissage->setCompetenceSuivie($competence);
            $apprentissage->setDateDebut($aujourdhui);
            $entityManager->persist($apprentissage);
            $entityManager->flush($apprentissage);
            
            $apprenant->addApprentissage($apprentissage);

            return $this->render('apprentissage/show.html.twig', [
                'apprentissage' => $apprentissage,
            ]);
        }
        
        return $this->redirectToRoute('competences_suivies');
    }

    /**
     * @Route("/apprentissage/{id}", name="show_apprentissage")
     */
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        // on récupère l'objet de la classe "apprentissage" ayant pour id "$id"
        $apprentissage = $doctrine->getRepository(Apprentissage::class)->find($id);

        if (! $apprentissage) {
            throw $this->createNotFoundException(
                "Aucun apprentissage répertorié avec l'id $id"
            );
        }

        // notre méthode rend le template "apprentissage/show.html.twig" où on pourra afficher notre objet apprentissage avec "{{ apprentissage }}"
        return $this->render('apprentissage/show.html.twig', [
            'apprentissage' => $apprentissage,
        ]);
    }
}
