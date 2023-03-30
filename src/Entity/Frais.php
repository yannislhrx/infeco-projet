<?php

namespace App\Entity;

use App\Repository\FraisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FraisRepository::class)
 */
class Frais
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
    private $pourcentage;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_appartement;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_agence;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPourcentage(): ?int
    {
        return $this->pourcentage;
    }

    public function setPourcentage(int $pourcentage): self
    {
        $this->pourcentage = $pourcentage;

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

    public function getIdAgence(): ?int
    {
        return $this->id_agence;
    }

    public function setIdAgence(int $id_agence): self
    {
        $this->id_agence = $id_agence;

        return $this;
    }
}
