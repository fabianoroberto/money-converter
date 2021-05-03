<?php

declare(strict_types=1);

namespace spec\App\Service;

use App\Service\ArticleService;
use PhpSpec\ObjectBehavior;

class ArticleServiceSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ArticleService::class);
    }
}
