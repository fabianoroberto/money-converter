<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Entity\GbpPrice;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class MathBaseRequest
{
    /**
     * @JMS\Type("integer")
     * @Assert\NotNull(message="Pound price is required.")
     * @Assert\NotBlank
     */
    private int $poundValue1 = 0;

    /**
     * @JMS\Type("integer")
     * @Assert\NotNull(message="Shilling price is required.")
     * @Assert\NotBlank
     */
    private int $shillingValue1 = 0;

    /**
     * @JMS\Type("integer")
     * @Assert\NotNull(message="Pence price is required.")
     * @Assert\NotBlank
     */
    private int $penceValue1 = 0;

    /**
     * @JMS\Type("integer")
     * @Assert\NotNull(message="Pound price is required.")
     * @Assert\NotBlank
     */
    private int $poundValue2 = 0;

    /**
     * @JMS\Type("integer")
     * @Assert\NotNull(message="Shilling price is required.")
     * @Assert\NotBlank
     */
    private int $shillingValue2 = 0;

    /**
     * @JMS\Type("integer")
     * @Assert\NotNull(message="Pence price is required.")
     * @Assert\NotBlank
     */
    private int $penceValue2 = 0;

    public function getPrice1(): GbpPrice
    {
        return new GbpPrice($this->poundValue1, $this->shillingValue1, $this->penceValue1);
    }

    public function getPrice2(): GbpPrice
    {
        return new GbpPrice($this->poundValue2, $this->shillingValue2, $this->penceValue2);
    }
}
