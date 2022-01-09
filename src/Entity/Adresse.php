<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=AdresseRepository::class)
 */
class Adresse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="adresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Zone::class, inversedBy="adresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $zone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $indication;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $fullAddresse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getIndication(): ?string
    {
        return $this->indication;
    }

    public function setIndication(?string $indication): self
    {
        $this->indication = $indication;

        return $this;
    }

    public function getFullAddresse(): ?string
    {
        return $this->fullAddresse;
    }

    public function setFullAddresse(string $fullAddresse): self
    {
        $this->fullAddresse = $fullAddresse;

        return $this;
    }
}
