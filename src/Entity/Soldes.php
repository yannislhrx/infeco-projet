<?php

namespace App\Entity;

use App\Repository\SoldesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SoldesRepository::class)
 */
class Soldes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $somme;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_entree;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_fin;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_locataire;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_appartement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSomme(): ?float
    {
        return $this->somme;
    }

    public function setSomme(float $somme): self
    {
        $this->somme = $somme;

        return $this;
    }

    public function getDateEntree(): ?\DateTimeInterface
    {
        return $this->date_entree;
    }

    public function setDateEntree(?\DateTimeInterface $date_entree): self
    {
        $this->date_entree = $date_entree;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getIdLocataire(): ?int
    {
        return $this->id_locataire;
    }

    public function setIdLocataire(int $id_locataire): self
    {
        $this->id_locataire = $id_locataire;

        return $this;
    }

    public function getIdAppartement(): ?int
    {
        return $this->id_appartement;
    }

    public function setIdAppartement(int $id_appartement): self
    {
        $this->id_appartement = $id_appartement;

        return $this;
    }
}
