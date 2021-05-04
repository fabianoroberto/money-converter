<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Request\ArticleCreateRequest;
use App\Dto\Request\ArticleUpdateRequest;
use App\Entity\Article;
use App\Entity\GbpPrice;
use App\Repository\ArticleRepositoryInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleService
{
    private FilesystemOperator $storage;

    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private LoggerInterface $logger,
        FilesystemOperator $articlesStorage,
    ) {
        $this->storage = $articlesStorage;
    }

    /**
     * @param bool|mixed $toBase64
     *
     * @throws FilesystemException
     */
    public function getPhoto(Article $article, bool $toBase64 = false): string
    {
        $content = $this->storage->read($article->getPhotoFilename());

        if ($toBase64) {
            $mimeType = $this->storage->mimeType($article->getPhotoFilename());

            return \sprintf('data: %s;base64,%s', $mimeType, \base64_encode($content));
        }

        return $content;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function store(ArticleCreateRequest $articleCreateRequest): Article
    {
        $this->logger->info('Create article');

        $price = new GbpPrice(
            $articleCreateRequest->getPoundValue(),
            $articleCreateRequest->getShillingValue(),
            $articleCreateRequest->getPenceValue()
        );

        $article = (new Article($articleCreateRequest->getName(), $price))
            ->setDescription($articleCreateRequest->getDescription());

        $this->articleRepository->store($article);

        return $article;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(ArticleUpdateRequest $articleUpdateRequest, Article $article): Article
    {
        $this->logger->info('Save article');

        $price = new GbpPrice(
            $articleUpdateRequest->getPoundValue(),
            $articleUpdateRequest->getShillingValue(),
            $articleUpdateRequest->getPenceValue()
        );

        $article->setName($articleUpdateRequest->getName())
            ->setDescription($articleUpdateRequest->getDescription())
            ->setPrice($price);

        $this->articleRepository->store($article);

        return $article;
    }

    /**
     * @throws OptimisticLockException
     * @throws FilesystemException
     * @throws ORMException
     */
    public function saveImage(Article $article, UploadedFile $image): Article
    {
        $imageName = \sprintf('%s.%s', $article->getUuid(), $image->guessExtension());

        $this->storage->write(
            $imageName,
            $image->getContent()
        );

        $article->setPhotoFilename($imageName);

        $this->articleRepository->store($article);

        return $article;
    }

    public function delete(Article $article): bool
    {
        try {
            if ($article->getPhotoFilename()) {
                $this->storage->delete($article->getPhotoFilename());
            }

            $this->articleRepository->delete($article);

            return true;
        } catch (FilesystemException $e) {
            $this->logger->error($e->getMessage());

            return false;
        } catch (ORMException $e) {
            $this->logger->error($e->getMessage());

            return false;
        }
    }
}
