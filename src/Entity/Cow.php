<?php

namespace App\Entity;

use App\Repository\CowRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CowRepository::class)]
#[UniqueEntity(fields: ['code'], message: 'Esse código já existe')]
class Cow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "O código é obrigatório")]
    #[Assert\Regex(pattern: "/^[A-Z]{3}-\d{4}-\d{4,6}$/",
        message: "Formato de código inválido (ex: FAZ-2025-0001)"
    )]
    private ?string $code = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Quantidade de leite é obrigatorio")]
    private ?float $milk = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Quantidade de comida é obrigatorio")]
    private ?float $food = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Peso do animal é obrigatorio")]
    private ?float $weight = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "Data de nascimento é obrigatorio")]
    private ?\DateTime $birth = null;

    #[ORM\ManyToOne(inversedBy: 'cows')]
    private ?Farm $farm = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getMilk(): ?float
    {
        return $this->milk;
    }

    public function setMilk(float $milk): static
    {
        $this->milk = $milk;

        return $this;
    }

    public function getFood(): ?float
    {
        return $this->food;
    }

    public function setFood(float $food): static
    {
        $this->food = $food;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getBirth(): ?\DateTime
    {
        return $this->birth;
    }

    public function setBirth(\DateTime $birth): static
    {
        $this->birth = $birth;

        return $this;
    }

    public function getFarm(): ?Farm
    {
        return $this->farm;
    }

    public function setFarm(?Farm $farm): static
    {
        $this->farm = $farm;

        return $this;
    }
}
