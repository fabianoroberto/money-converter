<?php

declare(strict_types=1);

namespace spec\App\Service;

use App\Repository\ArticleRepositoryInterface;
use App\Service\ArticleService;
use League\Flysystem\FilesystemOperator;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;

class ArticleServiceSpec extends ObjectBehavior
{
    public function let(
        ArticleRepositoryInterface $articleRepository,
        LoggerInterface $logger,
        FilesystemOperator $storage
    ) {
        $this->beConstructedWith($articleRepository, $logger, $storage);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ArticleService::class);
    }
}
