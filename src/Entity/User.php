<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="expediteur")
     */
    private $messagesEnvoyes;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="destinataire")
     */
    private $messagesReceptionnes;

    /**
     * @ORM\OneToMany(targetEntity=Competence::class, mappedBy="concepteur")
     */
    private $competencesCrees;

    public function __construct()
    {
        $this->messagesEnvoyes = new ArrayCollection();
        $this->messagesReceptionnes = new ArrayCollection();
        $this->competencesCrees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesEnvoyes(): Collection
    {
        return $this->messagesEnvoyes;
    }

    public function addMessagesEnvoye(Message $messagesEnvoye): self
    {
        if (!$this->messagesEnvoyes->contains($messagesEnvoye)) {
            $this->messagesEnvoyes[] = $messagesEnvoye;
            $messagesEnvoye->setExpediteur($this);
        }

        return $this;
    }

    public function removeMessagesEnvoye(Message $messagesEnvoye): self
    {
        if ($this->messagesEnvoyes->removeElement($messagesEnvoye)) {
            // set the owning side to null (unless already changed)
            if ($messagesEnvoye->getExpediteur() === $this) {
                $messagesEnvoye->setExpediteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesReceptionnes(): Collection
    {
        return $this->messagesReceptionnes;
    }

    public function addMessagesReceptionne(Message $messagesReceptionne): self
    {
        if (!$this->messagesReceptionnes->contains($messagesReceptionne)) {
            $this->messagesReceptionnes[] = $messagesReceptionne;
            $messagesReceptionne->setDestinataire($this);
        }

        return $this;
    }

    public function removeMessagesReceptionne(Message $messagesReceptionne): self
    {
        if ($this->messagesReceptionnes->removeElement($messagesReceptionne)) {
            // set the owning side to null (unless already changed)
            if ($messagesReceptionne->getDestinataire() === $this) {
                $messagesReceptionne->setDestinataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Competence>
     */
    public function getCompetencesCrees(): Collection
    {
        return $this->competencesCrees;
    }

    public function addCompetencesCree(Competence $competencesCree): self
    {
        if (!$this->competencesCrees->contains($competencesCree)) {
            $this->competencesCrees[] = $competencesCree;
            $competencesCree->setConcepteur($this);
        }

        return $this;
    }

    public function removeCompetencesCree(Competence $competencesCree): self
    {
        if ($this->competencesCrees->removeElement($competencesCree)) {
            // set the owning side to null (unless already changed)
            if ($competencesCree->getConcepteur() === $this) {
                $competencesCree->setConcepteur(null);
            }
        }

        return $this;
    }
}
