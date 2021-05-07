<?php

declare(strict_types=1);

namespace App\Dto\Traits;

use JMS\Serializer\Annotation as JMS;

trait DescriptionPropertyTrait
{
    /**
     * @JMS\Type("string")
     */
    protected string $description = '';

    public function getDescription(): string
    {
        return $this->description;
    }
}
