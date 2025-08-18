<?php

namespace App\Entity;

use App\Repository\FarmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FarmRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'O nome da fazenda já existe')]
class Farm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "O nome é obrigatorio")]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "O tamanho em Hectares é obrigatorio")]
    private ?float $size = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "O responsavel é obrigatorio")]
    private ?string $responsible = null;

    /**
     * @var Collection<int, Veterinarian>
     */
    #[ORM\ManyToMany(targetEntity: Veterinarian::class, inversedBy: 'farms')]
    private Collection $veterinarian;

    public function __construct()
    {
        $this->veterinarian = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(float $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getResponsible(): ?string
    {
        return $this->responsible;
    }

    public function setResponsible(string $responsible): static
    {
        $this->responsible = $responsible;

        return $this;
    }

    /**
     * @return Collection<int, Veterinarian>
     */
    public function getVeterinarian(): Collection
    {
        return $this->veterinarian;
    }

    public function addVeterinarian(Veterinarian $veterinarian): static
    {
        if (!$this->veterinarian->contains($veterinarian)) {
            $this->veterinarian->add($veterinarian);
        }

        return $this;
    }

    public function removeVeterinarian(Veterinarian $veterinarian): static
    {
        $this->veterinarian->removeElement($veterinarian);

        return $this;
    }
}
