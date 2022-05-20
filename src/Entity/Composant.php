<?php

namespace App\Entity;

use App\Repository\ComposantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComposantRepository::class)
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
     * @ORM\Column(type="json")
     */
    private $contenu = [];

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="composants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="composants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $competence;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="composantsCrees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $concepteur;

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

    public function getContenu(): ?array
    {
        return $this->contenu;
    }

    public function setContenu(array $contenu): self
    {
        $this->contenu = $contenu;

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

    public function getConcepteur(): ?User
    {
        return $this->concepteur;
    }

    public function setConcepteur(?User $concepteur): self
    {
        $this->concepteur = $concepteur;

        return $this;
    }
}