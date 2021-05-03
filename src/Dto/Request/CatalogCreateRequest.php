<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Dto\Traits\NamePropertyTrait;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class CatalogCreateRequest
{
    use NamePropertyTrait;

    /**
     * @JMS\Type("string")
     * @Assert\NotNull(message="Description is required.")
     * @Assert\NotBlank
     */
    protected string $description = '';

    public function getDescription(): string
    {
        return $this->description;
    }
}
