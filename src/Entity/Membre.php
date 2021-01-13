<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MembreRepository::class)
 */
class Membre
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
     * @ORM\Column(type="string", length=255)
     */
    private $role = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $email_verification;

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

    public function getNomprenom(): ?string
    {
        return $this->nom.' '.$this->prenom ;
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

    public function getRole(): array
    {
        $role = $this->role;
        // Garantie à tous les utilisateur d'avoir au moins le role de membre
        $role[] = 'membre';
        return array_unique($role);
    }

    public function setRole(array $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getEmailVerification(): ?int
    {
        return $this->email_verification;
    }

    public function setEmailVerification(int $email_verification): self
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
