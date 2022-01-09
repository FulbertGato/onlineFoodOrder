<?php

namespace App\Entity;

use App\Repository\ComplementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComplementRepository::class)
 */
class Complement extends Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TypeComplement::class, inversedBy="complements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeComplement;

    /**
     * @ORM\ManyToMany(targetEntity=Menu::class, mappedBy="complements")
     */
    private $menus;

    public function __construct()
    {
        parent::__construct();
        $this->menus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeComplement(): ?TypeComplement
    {
        return $this->typeComplement;
    }

    public function setTypeComplement(?TypeComplement $typeComplement): self
    {
        $this->typeComplement = $typeComplement;

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->addComplement($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeComplement($this);
        }

        return $this;
    }
}
