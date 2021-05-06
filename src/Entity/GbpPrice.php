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

    private ?int $remainderValue = 0;

    public function __construct(int $poundValue, int $shillingValue, int $penceValue)
    {
        $this->reduceAndAssign($poundValue, $shillingValue, $penceValue);
    }

    public function __toString(): string
    {
        if ($this->remainderValue > 0) {
            [$remainderPound, $remainderShilling, $reminderPence] = $this->reduce(0, 0, $this->remainderValue);

            if ($remainderPound > 0) {
                return \sprintf(
                    '%dp %ds %dd (%dp %ds %dd)',
                    $this->poundValue,
                    $this->shillingValue,
                    $this->penceValue,
                    $remainderPound,
                    $remainderShilling,
                    $reminderPence
                );
            }

            return \sprintf(
                '%dp %ds %dd (%ds %dd)',
                $this->poundValue,
                $this->shillingValue,
                $this->penceValue,
                $remainderShilling,
                $reminderPence
            );
        }

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
        $this->convertFromPence(\intdiv($dividend, $divisor), $dividend % $divisor);
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

    private function reduceAndAssign(int $poundValue, int $shillingValue, int $penceValue): void
    {
        [$poundValue, $shillingValue, $penceValue] = $this->reduce($poundValue, $shillingValue, $penceValue);

        $this->penceValue = $penceValue;
        $this->shillingValue = $shillingValue;
        $this->poundValue = $poundValue;
    }

    private function reduce(int $poundValue, int $shillingValue, int $penceValue): array
    {
        $penceQuotient = (int) ($penceValue / self::PENCE_TO_SHILLING);
        $newPenceValue = $penceValue % self::PENCE_TO_SHILLING;
        $shillingQuotient = (int) (($shillingValue + $penceQuotient) / self::SHILLING_TO_POUND);
        $newShillingValue = ($shillingValue + $penceQuotient) % self::SHILLING_TO_POUND;
        $newPoundValue = $poundValue + $shillingQuotient;

        return [$newPoundValue, $newShillingValue, $newPenceValue];
    }

    private function convertFromPence(int $value, int $remainderValue = 0): void
    {
        $this->reduceAndAssign(0, 0, $value);
        $this->remainderValue = $remainderValue;
    }

    private function convertToPence(int $poundValue, int $shillingValue, int $penceValue): int
    {
        return ((($poundValue * self::SHILLING_TO_POUND) + $shillingValue) * self::PENCE_TO_SHILLING) + $penceValue;
    }
}
