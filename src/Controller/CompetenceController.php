<?php

namespace App\Controller;

use DateTime;
use App\Entity\Composant;
use App\Entity\Competence;
use App\Form\CompetenceType;
use App\Form\SearchItemType;
use App\Service\FileUploader;
use App\Repository\ComposantRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CompetenceController extends AbstractController
{
    /**
     * @Route("/competence", name="index_competence")
     */
    public function index(ManagerRegistry $doctrine, Request $request, string $mots = NULL): Response
    {
        // on crée notre formulaire dédié à la recherche par mots-clés
        $form = $this->createForm(SearchItemType::class);
        // on récupère les données liées au formulaire
        $search = $form->handleRequest($request);

        // attention à ce passage
        $mots = $request->query->get('mots');

        // on s'assure que les données du formulaire respectent les contraintes de validation
        if ($form->isSubmitted() && $form->isValid()) {
            // on récupère les mots-clés de la recherche
            $mots = $search->get('mots')->getData();
            // on récupère tous les items correspondant aux mots-clés 
            $competences = $doctrine->getRepository(Competence::class)->search($mots);
            // on dirige l'utilisateur vers la première page correspondant à sa recherche par mots-clés
            $page = 1;
        }
        // on récupère tous les items correspondant aux mots-clés  
        elseif ($mots) {
            $competences = $doctrine->getRepository(Competence::class)->search($mots);
        } 
        else {
            $competences = $doctrine->getRepository(Competence::class)->findAll();
        }

        return $this->render('competence/index.html.twig', [
            'competences' => $competences,
            'searchForm' => $form->createView(),
            'mots' => $mots,      
        ]);
    }

/**
 * @IsGranted("ROLE_CREATOR")
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

    // dans le cas où on édite une compétence existante, on va également récupérer les composants qui lui ont été affectés
    $composantsActuels = $competence->getComposants();
    
    // je crée un tableau me permettant de "photographier" la liste des composants de la compétence avant update : l'objet $composantsActuels étant lié à la compétence, lorsqu'on ajoute / retire un composant de la compétence, il est également ajouté / retiré de $composantsActuels
    $composantsAvantUpdateArray = $composantsActuels->toArray();

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

        // on définit le nouveau nom des fichiers chargés et on les enregistre dans notre base de données         
        /** @var UploadedFile $imageFile */
        // on récupère les données correspondant au champ dévolu à l'upload de fichier de notre formulaire
        $imageFile = $form->get('imageFilename')->getData();
        // si ces données existent (cad s'il y a effectivement eu upload d'un fichier), il faut désormais lui attribuer un emplacement et sauvegarder le nom du fichier, après s'être assuré de l'unicité de celui-ci, dans notre base de données
        if ($imageFile) {
            // on upload le fichier dans le dossier adapté et on récupère le nouveau nom du fichier après l'ajout d'une chaîne de caractères pour s'assurer de son unicité
            $imageFileName = $fileUploader->upload($imageFile, "/competence/img");
            // on sauvegarde le nouveau nom de fichier affilié à notre compétence dans notre base de données
            $competence->setImageFilename($imageFileName);
        }
        /** @var UploadedFile $iconeFile */
        $iconeFile = $form->get('iconeFilename')->getData();
        if ($iconeFile) {
            $iconeFileName = $fileUploader->upload($iconeFile, "/competence/icon");
            $competence->setIconeFilename($iconeFileName);
        }

        // mise à jour de la liste de composants de la compétence

        // dans le cas d'une édition de compétence, on "libère" tous les composants de l'ancienne liste des composants de la compétence
        foreach ($composantsAvantUpdateArray as $composantAvantUpdate) {
            $composantAvantUpdate->removeCompetence($competence);
        }

        // puis on parcourt la liste actualisée des composants et on les lie à la compétence
        foreach ($composantsActuels as $index => $composant) {
            $composant->setCompetence($competence);
        }

        // on définit l'utilisateur connecté comme concepteur
        $competence->setConcepteur($user);
        // on définit la date du jour comme date de création de la compétence
        $competence->setDateCreation($aujourdhui);

        // on prépare l'objet à l'enregistrement
        $entityManager->persist($competence);
        // on enregistre dans notre base de données
        $entityManager->flush();

        // une fois la compétence créée, on redirige l'utilisateur vers la liste des compétences qu'il a créées, triées de la plus récente vers la plus ancienne
        return $this->redirectToRoute('mes_competences');
    }

    // lorsqu'on souhaite créer une compétence, on aura besoin dans notre vue : du formulaire, des composants disponibles (cad non attribués à une autre compétence) ; lorsqu'on édite une compétence, on a également besoin des composants attribués à cette compétence
    return $this->render('competence/add.html.twig', [
        'formCompetence' => $form->createView(),
        'composantsDisponibles' => $composantsDisponibles,
        'composantsActuels' => $composantsActuels,
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
    public function removeComposant(ManagerRegistry $doctrine, Composant $composant, Competence $competence)
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

    /**
     * @Route("/competence/delete/{id}", name="delete_competence")
     */
    public function delete(ManagerRegistry $doctrine, Competence $competence): Response
    {
        $entityManager = $doctrine->getManager();
        $composants = $competence->getComposants();

        foreach ($composants as $composant) {
            $composant->removeCompetence($competence);
        }
        $entityManager->remove($competence);
        $entityManager->flush();

        // on se sert de la méthode headers de l'objet request pour rediriger l'utilisateur sur la page d'où il vient
        return $this->redirectToRoute('mes_competences');
    }
}
