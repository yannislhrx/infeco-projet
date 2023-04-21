<?php

namespace App\Entity;

use App\Repository\AppartementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppartementRepository::class)
 */
class Appartement
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
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $complement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $code_postal;

    /**
     * @ORM\Column(type="float")
     */
    private $loyer;

    /**
     * @ORM\Column(type="integer")
     */
    private $charges;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_agence;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $id_str_locataires;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): self
    {
        $this->complement = $complement;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getLoyer(): ?float
    {
        return $this->loyer;
    }

    public function setLoyer(float $loyer): self
    {
        $this->loyer = $loyer;

        return $this;
    }

    public function getCharges(): ?int
    {
        return $this->charges;
    }

    public function setCharges(int $charges): self
    {
        $this->charges = $charges;

        return $this;
    }

    public function getIdAgence(): ?int
    {
        return $this->id_agence;
    }

    public function setIdAgence(?int $id_agence): self
    {
        $this->id_agence = $id_agence;

        return $this;
    }

    public function getIdStrLocataires(): ?string
    {
        return $this->id_str_locataires;
    }

    public function setIdStrLocataires(?string $id_str_locataires): self
    {
        $this->id_str_locataires = $id_str_locataires;

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
