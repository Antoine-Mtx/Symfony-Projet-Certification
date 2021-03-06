<?php

namespace App\Entity;

use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 * @ORM\Table(name="competence", indexes={@ORM\Index(columns={"intitule", "description"}, flags={"fulltext"})})
 */
class Competence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $intitule;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageFilename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iconeFilename;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="competencesCrees")
     * @ORM\JoinColumn(nullable=true)
     */
    private $concepteur;

    /**
     * @ORM\ManyToOne(targetEntity=Domaine::class, inversedBy="competences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $domaine;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="competence")
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Composant::class, mappedBy="competence", cascade={"persist"})
     */
    private $composants;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $synopsis;

    /**
     * @ORM\OneToMany(targetEntity=Apprentissage::class, mappedBy="competenceSuivie")
     */
    private $apprentissages;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->composants = new ArrayCollection();
        $this->apprentissages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }

    public function getIconeFilename(): ?string
    {
        return $this->iconeFilename;
    }

    public function setIconeFilename(?string $iconeFilename): self
    {
        $this->iconeFilename = $iconeFilename;

        return $this;
    }

    public function getConcepteur(): ?User
    {
        return $this->concepteur;
    }

    public function setConcepteur(?User $concepteur): self
    {
        $this->concepteur = $concepteur;

        return $this;
    }

    public function getDomaine(): ?Domaine
    {
        return $this->domaine;
    }

    public function setDomaine(?Domaine $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setCompetence($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getCompetence() === $this) {
                $commentaire->setCompetence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Composant>
     */
    public function getComposants(): Collection
    {
        return $this->composants;
    }

    public function addComposant(Composant $composant): self
    {
        if (!$this->composants->contains($composant)) {
            $this->composants[] = $composant;
            $composant->setCompetence($this);
        }

        return $this;
    }

    public function removeComposant(Composant $composant): self
    {
        if ($this->composants->removeElement($composant)) {
            // set the owning side to null (unless already changed)
            if ($composant->getCompetence() === $this) {
                $composant->setCompetence(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return ucfirst($this->intitule);
    }

    // public function toDisplay()
    // {
    //     include "../competence/card.html.twig";
    // }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * @return Collection<int, Apprentissage>
     */
    public function getapprentissages(): Collection
    {
        return $this->apprentissages;
    }

    public function addapprentissage(Apprentissage $apprentissage): self
    {
        if (!$this->apprentissages->contains($apprentissage)) {
            $this->apprentissages[] = $apprentissage;
            $apprentissage->setCompetenceSuivie($this);
        }

        return $this;
    }

    public function removeapprentissage(Apprentissage $apprentissage): self
    {
        if ($this->apprentissages->removeElement($apprentissage)) {
            // set the owning side to null (unless already changed)
            if ($apprentissage->getCompetenceSuivie() === $this) {
                $apprentissage->setCompetenceSuivie(null);
            }
        }

        return $this;
    }
}
