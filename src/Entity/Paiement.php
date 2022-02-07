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
     * @ORM\Column(type="string", length=255)
     */
    private $idPaiement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $methode;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity=Commande::class, inversedBy="paiement", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlPaiement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $qrCode;

    

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPaiement(): ?string
    {
        return $this->idPaiement;
    }

    public function setIdPaiement(string $idPaiement): self
    {
        $this->idPaiement = $idPaiement;

        return $this;
    }

    public function getMethode(): ?string
    {
        return $this->methode;
    }

    public function setMethode(string $methode): self
    {
        $this->methode = $methode;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getUrlPaiement(): ?string
    {
        return $this->urlPaiement;
    }

    public function setUrlPaiement(?string $urlPaiement): self
    {
        $this->urlPaiement = $urlPaiement;

        return $this;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function setQrCode(string $qrCode): self
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    
    
}
