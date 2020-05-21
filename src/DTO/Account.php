<?php

declare(strict_types=1);

namespace Genkgo\Camt\DTO;

/**
 * Class Account
 * @package Genkgo\Camt\DTO
 */
abstract class Account
{
    /**
     * @var \Genkgo\Camt\DTO\AccountOwner|null
     */
    private $owner;
    /**
     * @var \Genkgo\Camt\DTO\AccountServicer|null
     */
    private $servicer;
    /**
     * @var string|null
     */
    private $currency;

    /**
     * @return string
     */
    abstract public function getIdentification(): string;


    /**
     * @return \Genkgo\Camt\DTO\AccountOwner|null
     */
    public function getOwner(): ?\Genkgo\Camt\DTO\AccountOwner
    {
        return $this->owner;
    }

    /**
     * @param \Genkgo\Camt\DTO\AccountOwner|null $owner
     */
    public function setOwner(\Genkgo\Camt\DTO\AccountOwner $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return \Genkgo\Camt\DTO\AccountServicer|null
     */
    public function getServicer(): ?\Genkgo\Camt\DTO\AccountServicer
    {
        return $this->servicer;
    }

    /**
     * @param \Genkgo\Camt\DTO\AccountServicer|null $servicer
     */
    public function setServicer(\Genkgo\Camt\DTO\AccountServicer $servicer): void
    {
        $this->servicer = $servicer;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
}
