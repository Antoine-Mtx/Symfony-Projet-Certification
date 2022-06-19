<?php

namespace App\Entity;

use App\Repository\DomaineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DomaineRepository::class)
 */
class Domaine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Get creative and think of a title!")
     */
    private $intitule;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="Get creative and think of a title!")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageFilename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iconeFilename;

    /**
     * @ORM\OneToMany(targetEntity=Competence::class, mappedBy="domaine")
     */
    private $competences;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->iconeFilename = "no camera icon.png";
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    /**
     * @return Collection<int, Competence>
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
            $competence->setDomaine($this);
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        if ($this->competences->removeElement($competence)) {
            // set the owning side to null (unless already changed)
            if ($competence->getDomaine() === $this) {
                $competence->setDomaine(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return ucfirst($this->intitule);
    }
}
