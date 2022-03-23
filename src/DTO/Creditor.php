<?php

declare(strict_types=1);

namespace Genkgo\Camt\DTO;

class Creditor implements RelatedPartyTypeInterface
{
    private ?string $id;

    private ?string $name;

    private ?Address $address = null;

    private ?string $typeName;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }

    public function setTypeName(?string $typeName): void
    {
        $this->typeName = $typeName;
    }
}
