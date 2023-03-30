<?php

namespace App\Entity;

use App\Repository\DepotGarantitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepotGarantitRepository::class)
 */
class DepotGarantit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_locataire;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_appartement;

    /**
     * @ORM\Column(type="float")
     */
    private $somme;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSomme(): ?float
    {
        return $this->somme;
    }

    public function setSomme(float $somme): self
    {
        $this->somme = $somme;

        return $this;
    }
}
