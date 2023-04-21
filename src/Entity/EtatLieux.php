<?php

namespace App\Entity;

use App\Repository\EtatLieuxRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatLieuxRepository::class)
 */
class EtatLieux
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $remarque;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quand;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_locataire;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_appartement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): self
    {
        $this->remarque = $remarque;

        return $this;
    }

    public function getQuand(): ?string
    {
        return $this->quand;
    }

    public function setQuand(?string $quand): self
    {
        $this->quand = $quand;

        return $this;
    }

    public function getIdLocataire(): ?int
    {
        return $this->id_locataire;
    }

    public function setIdLocataire(?int $id_locataire): self
    {
        $this->id_locataire = $id_locataire;

        return $this;
    }

    public function getIdAppartement(): ?int
    {
        return $this->id_appartement;
    }

    public function setIdAppartement(?int $id_appartement): self
    {
        $this->id_appartement = $id_appartement;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(?int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
