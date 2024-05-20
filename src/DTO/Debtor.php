<?php

declare(strict_types=1);

namespace Genkgo\Camt\DTO;

class Debtor implements RelatedPartyTypeInterface
{
    private ?string $id = null;

    private ?Address $address = null;

    private ?string $typeName = null;

    public function __construct(private ?string $name)
    {
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
