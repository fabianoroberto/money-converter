<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */
class GbpPrice
{
    private const SHILLING_TO_POUND = 20;
    private const PENCE_TO_SHILLING = 12;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $poundValue = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $shillingValue = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $penceValue = 0;

    public function __construct(int $poundValue, int $shillingValue, int $penceValue)
    {
        $this->reduce($poundValue, $shillingValue, $penceValue);
    }

    public function __toString(): string
    {
        return \sprintf('%dp %ds %dd', $this->poundValue, $this->shillingValue, $this->penceValue);
    }

    public function add(int $poundValue, int $shillingValue, int $penceValue)
    {
        $addend1 = $this->convertToPence($this->poundValue, $this->shillingValue, $this->penceValue);
        $addend2 = $this->convertToPence($poundValue, $shillingValue, $penceValue);

        $this->convertFromPence($addend1 + $addend2);
    }

    public function sub(int $poundValue, int $shillingValue, int $penceValue)
    {
        $minuend = $this->convertToPence($this->poundValue, $this->shillingValue, $this->penceValue);
        $subtrahend = $this->convertToPence($poundValue, $shillingValue, $penceValue);

        Assert::greaterThanEq($minuend, $subtrahend, 'Minuend cannot less than subtrahend');

        $this->convertFromPence($minuend - $subtrahend);
    }

    public function mul(int $poundValue, int $shillingValue, int $penceValue)
    {
        $factor1 = $this->convertToPence($this->poundValue, $this->shillingValue, $this->penceValue);
        $factor2 = $this->convertToPence($poundValue, $shillingValue, $penceValue);

        $this->convertFromPence($factor1 * $factor2);
    }

    public function div(int $poundValue, int $shillingValue, int $penceValue)
    {
        $dividend = $this->convertToPence($this->poundValue, $this->shillingValue, $this->penceValue);
        $divisor = $this->convertToPence($poundValue, $shillingValue, $penceValue);

        Assert::greaterThanEq($dividend, $divisor, 'Dividend cannot less than divisor');
        $this->convertFromPence((int) $dividend / $divisor);
    }

    public function getPoundValue(): ?int
    {
        return $this->poundValue;
    }

    public function getShillingValue(): ?int
    {
        return $this->shillingValue;
    }

    public function getPenceValue(): ?int
    {
        return $this->penceValue;
    }

    private function reduce(int $poundValue, int $shillingValue, int $penceValue)
    {
        $penceQuotient = (int) ($penceValue / self::PENCE_TO_SHILLING);
        $this->penceValue = $penceValue % self::PENCE_TO_SHILLING;
        $shillingQuotient = (int) (($shillingValue + $penceQuotient) / self::SHILLING_TO_POUND);
        $this->shillingValue = ($shillingValue + $penceQuotient) % self::SHILLING_TO_POUND;
        $this->poundValue = $poundValue + $shillingQuotient;
    }

    private function convertFromPence(int $value): void
    {
        $this->reduce(0, 0, $value);
    }

    private function convertToPence(int $poundValue, int $shillingValue, int $penceValue): int
    {
        return ((($poundValue * self::SHILLING_TO_POUND) + $shillingValue) * self::PENCE_TO_SHILLING) + $penceValue;
    }
}
