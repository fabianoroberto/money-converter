<?php

declare(strict_types=1);

namespace App\Dto\Traits;

use App\Entity\GbpPrice;
use JMS\Serializer\Annotation as JMS;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

trait PricePropertyTrait
{
    /**
     * @JMS\Type("string")
     * @Assert\NotNull(message="Price is required.")
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
    private string $price;

    public function getPrice(): GbpPrice
    {
        $parts = \explode(' ', $this->price);

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
}
