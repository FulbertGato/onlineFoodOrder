<?php

namespace App\Entity;

use App\Repository\BurgerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=BurgerRepository::class)
 */
class Burger extends Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank
     */
    private $tempsCuisson;

    /**
     * @ORM\OneToMany(targetEntity=Menu::class, mappedBy="burger", orphanRemoval=true)
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

    public function getTempsCuisson(): ?\DateTimeInterface
    {
        return $this->tempsCuisson;
    }

    public function setTempsCuisson(\DateTimeInterface $tempsCuisson): self
    {
        $this->tempsCuisson = $tempsCuisson;

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
            $menu->setBurger($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getBurger() === $this) {
                $menu->setBurger(null);
            }
        }

        return $this;
    }
}
