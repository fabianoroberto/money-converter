<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Entity\GbpPrice;
use JMS\Serializer\Annotation as JMS;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

final class MathDivRequest
{
    /**
     * @JMS\Type("string")
     * @Assert\NotNull(message="Dividend is required.")
     * @Assert\NotBlank
     * @Assert\Regex(
     *     pattern="/^\d+p \d+s \d+d$/",
     *     message="Price format should be Xp Ys Zd",
     * )
     * @OA\Property(
     *     type="string",
     *     pattern="^\d+p \d+s \d+d$",
     *     example="18p 16s 1d"
     * )
     */
    private string $dividend;

    /**
     * @JMS\Type("string")
     * @Assert\NotNull(message="Divisor is required.")
     * @Assert\NotBlank
     * @Assert\Regex(
     *     pattern="/^\d+p \d+s \d+d|\d+$/",
     *     message="Price format should be Xp Ys Zd or an integer",
     * )
     * @OA\Property(
     *     type="string",
     *     pattern="^\d+p \d+s \d+d|\d+$",
     *     example="15"
     * )
     */
    private string $divisor;

    public function getPrice1(): GbpPrice
    {
        $parts = explode(' ', $this->dividend);

        $poundValue = 0;
        $shillingValue = 0;

        match (count($parts)) {
            1 => [$penceValue] = $parts,
            2 => [$shillingValue, $penceValue] = $parts,
            default => [$poundValue, $shillingValue, $penceValue] = $parts,
        };

        return new GbpPrice(
            (int) $poundValue,
            (int) $shillingValue,
            (int) $penceValue
        );
    }

    public function getPrice2(): GbpPrice
    {
        $parts = explode(' ', $this->divisor);

        $poundValue = 0;
        $shillingValue = 0;

        match (count($parts)) {
            1 => [$penceValue] = $parts,
            2 => [$shillingValue, $penceValue] = $parts,
            default => [$poundValue, $shillingValue, $penceValue] = $parts,
        };

        return new GbpPrice(
            (int) $poundValue,
            (int) $shillingValue,
            (int) $penceValue
        );
    }

    public function toArray(): array
    {
        return [
            'dividend' => $this->dividend,
            'divisor' => $this->divisor,
        ];
    }
}
