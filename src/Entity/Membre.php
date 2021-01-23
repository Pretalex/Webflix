<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=MembreRepository::class)
 */
class Membre implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudonyme;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mot_de_passe;

    /**
     * @ORM\Column(type="text")
     */
    private $roles = '[]';

    /**
     * @ORM\Column(type="boolean")
     */
    private $email_verification = false;

    /**
     * @ORM\OneToMany(targetEntity=EmailMembre::class, mappedBy="membre")
     */
    private $emailMembres;

    /**
     * @ORM\OneToMany(targetEntity=Paiement::class, mappedBy="membre")
     */
    private $paiements;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="auteur")
     */
    private $commentaires;

    public function __construct()
    {
        $this->emailMembres = new ArrayCollection();
        $this->paiements = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }


       /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = json_decode($this->roles, true);
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = json_encode($roles);

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->mot_de_passe;
    }

    public function setPassword(string $password): self
    {
        $this->mot_de_passe = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNomprenom(): ?string
    {
        return $this->nom.' '.$this->prenom ;
    }

    public function hasAlreadyVotedForFilm(Film $film): bool
    {
        foreach ($this->getCommentaires() as $commentaire) {
            if ($commentaire->getFilm() === $film){
                return true;
            }
        }
        return false;
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getPseudonyme(): ?string
    {
        return $this->pseudonyme;
    }

    public function setPseudonyme(string $pseudonyme): self
    {
        $this->pseudonyme = $pseudonyme;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    public function getEmailVerification(): bool
    {
        return $this->email_verification;
    }

    public function setEmailVerification(bool $email_verification): self
    {
        $this->email_verification = $email_verification;

        return $this;
    }

    /**
     * @return Collection|EmailMembre[]
     */
    public function getEmailMembres(): Collection
    {
        return $this->emailMembres;
    }

    public function addEmailMembre(EmailMembre $emailMembre): self
    {
        if (!$this->emailMembres->contains($emailMembre)) {
            $this->emailMembres[] = $emailMembre;
            $emailMembre->setMembre($this);
        }

        return $this;
    }

    public function removeEmailMembre(EmailMembre $emailMembre): self
    {
        if ($this->emailMembres->removeElement($emailMembre)) {
            // set the owning side to null (unless already changed)
            if ($emailMembre->getMembre() === $this) {
                $emailMembre->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Paiement[]
     */
    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): self
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements[] = $paiement;
            $paiement->setMembre($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): self
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getMembre() === $this) {
                $paiement->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setAuteur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getAuteur() === $this) {
                $commentaire->setAuteur(null);
            }
        }

        return $this;
    }
}
