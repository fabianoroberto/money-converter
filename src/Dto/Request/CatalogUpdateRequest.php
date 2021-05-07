<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Dto\Traits\DescriptionPropertyTrait;
use App\Dto\Traits\NamePropertyTrait;

class CatalogUpdateRequest
{
    use DescriptionPropertyTrait;
    use NamePropertyTrait;
}
