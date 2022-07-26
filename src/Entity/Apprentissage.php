<?php

namespace App\Entity;

use App\Repository\ApprentissageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApprentissageRepository::class)
 */
class Apprentissage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $progression;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="competencesSuivies")
     * @ORM\JoinColumn(nullable=true)
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="apprenants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $competenceSuivie;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    public function __construct()
    {
        $this->progression = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProgression(): ?float
    {
        return $this->progression;
    }

    public function setProgression(float $progression): self
    {
        $this->progression = $progression;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getApprenant(): ?User
    {
        return $this->apprenant;
    }

    public function setApprenant(?User $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getCompetenceSuivie(): ?Competence
    {
        return $this->competenceSuivie;
    }

    public function setCompetenceSuivie(?Competence $competenceSuivie): self
    {
        $this->competenceSuivie = $competenceSuivie;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }
    public function __toString()
    {
        return ucfirst($this->competenceSuivie->getIntitule());
    }
}
