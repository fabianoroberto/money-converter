<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Entity\GbpPrice;
use JMS\Serializer\Annotation as JMS;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

final class MathSumRequest
{
    /**
     * @JMS\Type("string")
     * @Assert\NotNull(message="First addend is required.")
     * @Assert\NotBlank
     * @Assert\Regex(
     *     pattern="/^\d+p \d+s \d+d$/",
     *     message="Price format should be Xp Ys Zd",
     * )
     * @OA\Property(
     *     type="string",
     *     pattern="^\d+p \d+s \d+d$",
     *     example="5p 17s 8d"
     * )
     */
    private string $addend1;

    /**
     * @JMS\Type("string")
     * @Assert\NotNull(message="Second addend is required.")
     * @Assert\NotBlank
     * @Assert\Regex(
     *     pattern="/^\d+p \d+s \d+d|\d+$/",
     *     message="Price format should be Xp Ys Zd or an integer",
     * )
     * @OA\Property(
     *     type="string",
     *     pattern="^\d+p \d+s \d+d|\d+$",
     *     example="3p 4s 10d"
     * )
     */
    private string $addend2;

    public function getPrice1(): GbpPrice
    {
        $parts = \explode(' ', $this->addend1);

        $poundValue = 0;
        $shillingValue = 0;

        match (\count($parts)) {
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
        $parts = \explode(' ', $this->addend2);

        $poundValue = 0;
        $shillingValue = 0;

        match (\count($parts)) {
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
            'addend1' => $this->addend1,
            'addend2' => $this->addend2,
        ];
    }
}
