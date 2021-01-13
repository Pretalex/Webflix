<?php

namespace App\Entity;
use DateTime;
use DateTimeInterface;
use App\Repository\EmailMembreRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmailMembreRepository::class)
 */
class EmailMembre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=membre::class, inversedBy="emailMembres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sujet_email;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_email;

    public function __construct() {
        $this->date_email = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMembre(): ?membre
    {
        return $this->membre;
    }

    public function setMembre(?membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }

    public function getSujetEmail(): ?string
    {
        return $this->sujet_email;
    }

    public function setSujetEmail(string $sujet_email): self
    {
        $this->sujet_email = $sujet_email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDateEmail(): ?\DateTimeInterface
    {
        return $this->date_email;
    }

    public function setDateEmail(\DateTimeInterface $date_email): self
    {
        $this->date_email = $date_email;

        return $this;
    }
}
