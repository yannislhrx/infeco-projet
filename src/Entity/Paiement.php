<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaiementRepository::class)
 */
class Paiement
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
     * @ORM\Column(type="string", length=255)
     */
    private $emmeteur;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_appartement;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_locataire;

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

    public function getEmmeteur(): ?string
    {
        return $this->emmeteur;
    }

    public function setEmmeteur(string $emmeteur): self
    {
        $this->emmeteur = $emmeteur;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getIdLocataire(): ?int
    {
        return $this->id_locataire;
    }

    public function setIdLocataire(int $id_locataire): self
    {
        $this->id_locataire = $id_locataire;

        return $this;
    }
}
