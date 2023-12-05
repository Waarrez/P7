<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: Types::STRING)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'brand', cascade: ['persist', 'remove'])]
    private ?Phone $phone = null;

    public function __construct() {
        $this->id = Ulid::generate();
    }

    public function getId(): ?string
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

    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    public function setPhone(Phone $phone): static
    {
        // set the owning side of the relation if necessary
        if ($phone->getBrand() !== $this) {
            $phone->setBrand($this);
        }

        $this->phone = $phone;

        return $this;
    }
}
