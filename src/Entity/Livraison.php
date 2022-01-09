<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LivraisonRepository::class)
 */
class Livraison
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TypeLivraison::class, inversedBy="livraisons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Adresse::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $addresse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?TypeLivraison
    {
        return $this->type;
    }

    public function setType(?TypeLivraison $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAddresse(): ?Adresse
    {
        return $this->addresse;
    }

    public function setAddresse(?Adresse $addresse): self
    {
        $this->addresse = $addresse;

        return $this;
    }
}
