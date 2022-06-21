<?php

namespace App\Controller;

use DateTime;
use App\Entity\Composant;
use App\Entity\Competence;
use App\Form\CompetenceType;
use App\Service\FileUploader;
use App\Repository\ComposantRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    public function add(ManagerRegistry $doctrine, ComposantRepository $composantRepository, Competence $competence = NULL, Request $request, FileUploader $fileUploader) {

        // si la compétence existe on est dans le cas "update", sinon on est dans le cas "add" et il faut créer une instance de la classe Competence
        if (! $competence) {
            $competence = new Competence();
        }

        // on va utiliser doctrine pour ajouter / actualiser notre compétence au niveau de la base de données
        $entityManager = $doctrine->getManager();
        // une compétence étant un assemblage de composants, on se sert du repository de Composant pour rechercher les composants dont on a besoin ET qui n'ont pas déjà été affectés à une autre compétence
        $composantsDisponibles = $composantRepository->findBy(['competence' => NULL]);

        // on crée un formulaire dévolu à l'ajout de compétence
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        // on récupère l'utilisateur connecté
        $user = $this->getUser();
        // on récupère la date du jour
        $aujourdhui = new DateTime();

        // on vérifie que le formulaire rempli est conforme
        if ($form->isSubmitted() && $form->isValid()) {
            // on définit les attributs de notre objet competence avec les données du formulaires
            $competence = $form->getData();

            // on définit l'emplacement des fichiers chargés et on enregistre en base de données le chemin pour y a accéder          
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageFilename')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile, "/competence/img");
                $competence->setImageFilename($imageFileName);
            }
            /** @var UploadedFile $iconeFile */
            $iconeFile = $form->get('iconeFilename')->getData();
            if ($iconeFile) {
                $iconeFileName = $fileUploader->upload($iconeFile, "/competence/icon");
                $competence->setIconeFilename($iconeFileName);
            }

            // on récupère la liste des composants qui constituent notre compétence
            $composantsAjoutes = $form->get('composants')->getData();

            // on parcourt cette liste et on renseigne la propriété compétence de chacun de ces composants
            foreach ($composantsAjoutes as $index => $composant) {
                $composantAhydrater = $composantRepository->find($composant);
                $composantAhydrater->setCompetence($competence);
            }

            // on définit l'utilisateur connecté comme concepteur
            $competence->setConcepteur($user);
            // on définit la date du jour comme date de création de la compétence
            $competence->setDateCreation($aujourdhui);

            // on prépare l'objet à l'enregistrement
            $entityManager->persist($competence);
            // on l'enregistre dans notre base de données
            $entityManager->flush();

            // une fois la compétence créée, on redirige l'utilisateur vers la liste des compétences triée de la plus récente vers la plus ancienne
            return $this->redirectToRoute('index_competence');
        }

        return $this->render('competence/add.html.twig', [
            'formCompetence' => $form->createView(),
            'title' => "Ajouter",
            // 'competenceId' => $competence->getId(),
            'composantsDisponibles' => $composantsDisponibles,
        ]);
    }

    /**
     * @Route("/competence/{idCompetence}/addComposant/{idComposant}", name="add_composant_to_competence")
     * @ParamConverter("competence", options={"mapping": {"idCompetence": "id"}})
     * @ParamConverter("composant", options={"mapping": {"idComposant": "id"}})
     */
    public function addComposant(ManagerRegistry $doctrine, ComposantRepository $composantRepository, Composant $composant, Competence $competence, Request $request)
    {
        $entityManager = $doctrine->getManager();
        $competence->addComposant($composant);
        $entityManager->flush();

        $composants = $composantRepository->findAll();

        return $this->redirectToRoute('show_competence', [
            'id' => $competence->getId(),
            'composants' => $composants,
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
