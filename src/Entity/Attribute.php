<?php

namespace App\Entity;

use App\Repository\AttributeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AttributeRepository::class)
 */
class Attribute
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Facts::class, mappedBy="attribute")
     */
    private $facts;

    public function __construct()
    {
        $this->facts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Facts[]
     */
    public function getFacts(): Collection
    {
        return $this->facts;
    }

    public function addFact(Facts $fact): self
    {
        if (!$this->facts->contains($fact)) {
            $this->facts[] = $fact;
            $fact->setAttribute($this);
        }

        return $this;
    }

    public function removeFact(Facts $fact): self
    {
        if ($this->facts->removeElement($fact)) {
            // set the owning side to null (unless already changed)
            if ($fact->getAttribute() === $this) {
                $fact->setAttribute(null);
            }
        }

        return $this;
    }
}
