<?php

namespace App\Entity;

use App\Repository\ConfigurationVarRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigurationVarRepository::class)
 */
class ConfigurationVar
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $lastIdComplement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lastIdBurger;

    /**
     * @ORM\Column(type="integer")
     */
    private $lastIdMenu;

    /**
     * @ORM\Column(type="integer")
     */
    private $lastIdCommande;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payplugApi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mailjetApi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mailjetApiSecret;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastIdComplement(): ?string
    {
        return $this->lastIdComplement;
    }

    public function setLastIdComplement(?string $lastIdComplement): self
    {
        $this->lastIdComplement = $lastIdComplement;

        return $this;
    }

    public function getLastIdBurger(): ?int
    {
        return $this->lastIdBurger;
    }

    public function setLastIdBurger(?int $lastIdBurger): self
    {
        $this->lastIdBurger = $lastIdBurger;

        return $this;
    }

    public function getLastIdMenu(): ?int
    {
        return $this->lastIdMenu;
    }

    public function setLastIdMenu(int $lastIdMenu): self
    {
        $this->lastIdMenu = $lastIdMenu;

        return $this;
    }

    public function getLastIdCommande(): ?int
    {
        return $this->lastIdCommande;
    }

    public function setLastIdCommande(int $lastIdCommande): self
    {
        $this->lastIdCommande = $lastIdCommande;

        return $this;
    }

    public function getPayplugApi(): ?string
    {
        return $this->payplugApi;
    }

    public function setPayplugApi(string $payplugApi): self
    {
        $this->payplugApi = $payplugApi;

        return $this;
    }

    public function getMailjetApi(): ?string
    {
        return $this->mailjetApi;
    }

    public function setMailjetApi(string $mailjetApi): self
    {
        $this->mailjetApi = $mailjetApi;

        return $this;
    }

    public function getMailjetApiSecret(): ?string
    {
        return $this->mailjetApiSecret;
    }

    public function setMailjetApiSecret(string $mailjetApiSecret): self
    {
        $this->mailjetApiSecret = $mailjetApiSecret;

        return $this;
    }
}
