<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="facture")
     */
    private $username;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="facture")
     */
    private $product;

    public function __construct()
    {
        $this->username = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsername(): Collection
    {
        return $this->username;
    }

    public function addUsername(User $username): self
    {
        if (!$this->username->contains($username)) {
            $this->username[] = $username;
            $username->setFacture($this);
        }

        return $this;
    }

    public function removeUsername(User $username): self
    {
        if ($this->username->removeElement($username)) {
            // set the owning side to null (unless already changed)
            if ($username->getFacture() === $this) {
                $username->setFacture(null);
            }
        }

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
