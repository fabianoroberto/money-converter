<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Dto\Traits\CatalogsPropertyTrait;

final class ArticleCreateRequest extends ArticleUpdateRequest
{
    use CatalogsPropertyTrait;
}
