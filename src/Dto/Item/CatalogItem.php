<?php

declare(strict_types=1);

namespace App\Dto\Item;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class CatalogItem
{
    /**
     * @JMS\Type("string")
     * @Assert\NotNull(message="Slug is required.")
     * @Assert\NotBlank
     */
    private string $slug;

    public function getSlug(): string
    {
        return $this->slug;
    }
}
