<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FilmRepository::class)
 */
class Film
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
    private $titre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_de_sortie;

    /**
     * @ORM\Column(type="time")
     */
    private $duree;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bande_annonce;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $note_film = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $vus = 0;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="films")
     */
    private $genre;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="film")
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Paiement::class, mappedBy="film")
     */
    private $paiements;

    public function __construct()
    {
        $this->genre = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->paiements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateDeSortie(): ?\DateTimeInterface
    {
        return $this->date_de_sortie;
    }

    public function setDateDeSortie(?\DateTimeInterface $date_de_sortie): self
    {
        $this->date_de_sortie = $date_de_sortie;

        return $this;
    }

    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(\DateTimeInterface $duree): self
    {
        $this->duree = $duree;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getBandeAnnonce(): ?string
    {
        return $this->bande_annonce;
    }

    public function setBandeAnnonce(?string $bande_annonce): self
    {
        $this->bande_annonce = $bande_annonce;

        return $this;
    }

    public function getNoteFilm(): ?float
    {
        return $this->note_film;
    }

    public function setNoteFilm(?float $note_film): self
    {
        $this->note_film = $note_film;

        return $this;
    }

    public function getVus(): ?int
    {
        return $this->vus;
    }

    public function setVus(int $vus): self
    {
        $this->vus = $vus;

        return $this;
    }

    /**
     * @return Collection|genre[]
     */
    public function getGenre(): Collection
    {
        return $this->genre;
    }

    public function addGenre(genre $genre): self
    {
        if (!$this->genre->contains($genre)) {
            $this->genre[] = $genre;
        }

        return $this;
    }

    public function removeGenre(genre $genre): self
    {
        $this->genre->removeElement($genre);

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
            $commentaire->setFilm($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getFilm() === $this) {
                $commentaire->setFilm(null);
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
            $paiement->setFilm($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): self
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getFilm() === $this) {
                $paiement->setFilm(null);
            }
        }

        return $this;
    }
}
