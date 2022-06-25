<?php

namespace App\Entity;

use App\Repository\ComposantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComposantRepository::class)
 * @ORM\Table(name="composant", indexes={@ORM\Index(columns={"intitule", "textContent"}, flags={"fulltext"})})
 */
class Composant
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $filename;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="composants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="composants")
     * @ORM\JoinColumn(nullable=true)
     */
    private $competence;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="composantsCrees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $concepteur;

    /**
     * @ORM\Column(type="date")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $textContent;

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

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

        return $this;
    }

    public function removeCompetence(?Competence $competence): self
    {
        $this->competence = NULL;

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

    public function __toString()
    {
        return ucfirst($this->intitule);
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getTextContent(): ?string
    {
        return $this->textContent;
    }

    public function setTextContent(?string $textContent): self
    {
        $this->textContent = $textContent;

        return $this;
    }
}
