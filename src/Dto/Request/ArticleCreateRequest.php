<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Dto\Traits\NamePropertyTrait;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleCreateRequest
{
    use NamePropertyTrait;

    /**
     * @JMS\Type("string")
     * @Assert\NotNull(message="Description is required.")
     * @Assert\NotBlank
     */
    protected string $description = '';

    /**
     * @JMS\Type("integer")
     * @Assert\NotNull(message="Pound price is required.")
     * @Assert\NotBlank
     */
    private int $poundValue = 0;

    /**
     * @JMS\Type("integer")
     * @Assert\NotNull(message="Shilling price is required.")
     * @Assert\NotBlank
     */
    private int $shillingValue = 0;

    /**
     * @JMS\Type("integer")
     * @Assert\NotNull(message="Pence price is required.")
     * @Assert\NotBlank
     */
    private int $penceValue = 0;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPoundValue(): int
    {
        return $this->poundValue;
    }

    public function getShillingValue(): int
    {
        return $this->shillingValue;
    }

    public function getPenceValue(): int
    {
        return $this->penceValue;
    }
}
