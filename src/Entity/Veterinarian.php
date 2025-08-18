<?php

namespace App\Entity;

use App\Repository\VeterinarianRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VeterinarianRepository::class)]
#[UniqueEntity('crmv', message: "O CRMV deve ser unico")]
class Veterinarian
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "O nome é obrigatorio")]
    private ?string $name = null;

    #[ORM\Column(length: 20, unique: true)]
    #[Assert\Regex(pattern: '/^CRMV-[A-Z]{2}-\d{4,6}$/', message: "O CRMV deve ser valido")]
    private ?string $crmv = null;

    /**
     * @var Collection<int, Farm>
     */
    #[ORM\ManyToMany(targetEntity: Farm::class, mappedBy: 'veterinarian')]
    private Collection $farms;

    public function __construct()
    {
        $this->farms = new ArrayCollection();
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

    public function getCrmv(): ?string
    {
        return $this->crmv;
    }

    public function setCrmv(string $crmv): static
    {
        $this->crmv = $crmv;

        return $this;
    }

    /**
     * @return Collection<int, Farm>
     */
    public function getFarms(): Collection
    {
        return $this->farms;
    }

    public function addFarm(Farm $farm): static
    {
        if (!$this->farms->contains($farm)) {
            $this->farms->add($farm);
            $farm->addVeterinarian($this);
        }

        return $this;
    }

    public function removeFarm(Farm $farm): static
    {
        if ($this->farms->removeElement($farm)) {
            $farm->removeVeterinarian($this);
        }

        return $this;
    }
}
