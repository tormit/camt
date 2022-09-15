<?php

declare(strict_types=1);

namespace Genkgo\Camt;

use Iban\Validation\Iban as IbanDetails;
use InvalidArgumentException;

class Iban
{
    private string $iban;
    public static $validateIban = true;

    public function __construct(string $iban)
    {
        if (static::$validateIban) {
            if (!verify_iban($iban)) {
                throw new InvalidArgumentException("Unknown IBAN {$iban}");
            }
        }

        $ibanNormalized = iban_to_human_format($iban);
        $this->iban = $ibanNormalized;
    }

    public function getIban(): string
    {
        return $this->iban;
    }

    public function __toString(): string
    {
        return $this->iban;
    }

    public function equals(string $iban): bool
    {
        return (new IbanDetails($iban))->getNormalizedIban() === $this->iban;
    }
}
