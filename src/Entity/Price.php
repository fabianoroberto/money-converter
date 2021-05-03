<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use NumberFormatter;

/**
 * @ORM\Embeddable
 */
final class Price
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $value;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $currency = 'GBP';

    public function __construct(float $value, ?Currency $currencyObject = null)
    {
        $this->setFloatValue($value);

        if ($currencyObject !== null) {
            $this->currency = $currencyObject->getCode();
        }
    }

    public function __toString(): string
    {
        $currency = $this->currency ?? 'GBP';
        $fmt = new NumberFormatter('it_IT', NumberFormatter::CURRENCY);

        return $fmt->formatCurrency($this->getFloatValue(), $currency);
    }

    public function toArray(): ?array
    {
        if ($this->value === null) {
            return null;
        }

        return ['value' => $this->getFloatValue(), 'currency' => $this->currency];
    }

    public function getFloatValue(): float
    {
        return (float) ($this->value / 100);
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function setCurrencyObject(?Currency $currencyObject): self
    {
        $this->currency = $currencyObject->getCode();

        return $this;
    }

    private function setFloatValue(float $value)
    {
        $this->setValue((int) ($value * 100));
    }
}
