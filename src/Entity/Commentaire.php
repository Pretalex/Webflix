<?php

namespace App\Entity;
use DateTime;
use DateTimeInterface;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="datetime")
     */
    private $date_publication;

    /**
     * @ORM\Column(type="text")
     */
    private $texte;

    /**
     * @ORM\ManyToOne(targetEntity=Film::class, inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $film;

    /**
     * @ORM\ManyToOne(targetEntity=Membre::class, inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    public function __construct() {
        $this->date_publication = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getFilm(): ?film
    {
        return $this->film;
    }

    public function setFilm(?film $film): self
    {
        $this->film = $film;

        return $this;
    }

    public function getAuteur(): ?membre
    {
        return $this->auteur;
    }

    public function setAuteur(?membre $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }
}
