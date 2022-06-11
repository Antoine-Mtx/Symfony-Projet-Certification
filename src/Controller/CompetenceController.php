<?php

namespace App\Controller;

use App\Entity\Composant;
use App\Entity\Competence;
use App\Form\CompetenceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CompetenceController extends AbstractController
{
    /**
     * @Route("/competence", name="index_competence")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $competences = $doctrine->getRepository(Competence::class)->findAll(); // on désigne le repository de la classe "competence" à notre gestionnaire $doctrine puis on utilise la méthode findAll() pour récupérer toutes les instances de cette classe

        return $this->render('competence/index.html.twig', [
            'competences' => $competences,
        ]);
    }

    /**
     * @Route("/competence/add", name="add_competence")
     * @Route("/competence/update/{id}", name="update_competence")
     */
    public function add(ManagerRegistry $doctrine, Competence $competence = NULL, Request $request) {

        if (! $competence) {
            $competence = new Competence();
        }

        $entityManager = $doctrine->getManager();

        $form = $this->createForm(CompetenceType::class, $competence); // on crée un formulaire dévolu à l'ajout de "competence" de competence
        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $competence = $form->getData();
            $entityManager->persist($competence);
            $entityManager->flush();

            return $this->redirectToRoute('index_competence');
        }

        return $this->render('competence/add.html.twig', [
            'formCompetence' => $form->createView(),
            'title' => "Ajouter",
            'id' => $user->getId(),
            'competenceId' => $competence->getId(),
        ]);
    }

    /**
     * @Route("/competence/{idCompetence}/addComposant/{idComposant}", name="add_composant_to_competence")
     * @ParamConverter("competence", options={"mapping": {"idCompetence": "id"}})
     * @ParamConverter("composant", options={"mapping": {"idComposant": "id"}})
     */
    public function addComposant(ManagerRegistry $doctrine, Composant $composant, Competence $competence, Request $request)
    {
        $entityManager = $doctrine->getManager();
        $competence->addComposant($composant);
        $entityManager->flush();

        return $this->redirectToRoute('show_competence', [
            'id' => $competence->getId(),
        ]);
    }

    /**
     * @Route("/competence/{idCompetence}/removecomposant/{idComposant}", name="remove_composant_from_competence")
     * @ParamConverter("competence", options={"mapping": {"idCompetence": "id"}})
     * @ParamConverter("composant", options={"mapping": {"idComposant": "id"}})
     */
    public function removecomposant(ManagerRegistry $doctrine, Composant $composant, Competence $competence)
    {

        $entityManager = $doctrine->getManager();
        $competence->removecomposant($composant);
        $entityManager->remove($composant);
        $entityManager->persist($competence);
        $entityManager->flush();
        
        return $this->redirectToRoute('show_competence', [
            'id' => $competence->getId(),
        ]);
    }

    /**
     * @Route("/competence/{id}", name="show_competence")
     */
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $competence = $doctrine->getRepository(Competence::class)->find($id); // on récupère l'objet de la classe "competence" ayant pour id "$id"

        if (! $competence) {
            throw $this->createNotFoundException(
                "Aucun compétence répertorié avec l'id $id"
            );
        }

        return $this->render('competence/show.html.twig', [ // notre méthode rend le template "competence/show.html.twig" où on pourra afficher les incompetences accessibles de notre objet competence avec "{{ competence }}"
            'competence' => $competence,
        ]);
    }
}
