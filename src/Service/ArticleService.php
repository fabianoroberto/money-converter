<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Request\ArticleAddToCatalogRequest;
use App\Dto\Request\ArticleCreateRequest;
use App\Dto\Request\ArticleRemoveFromCatalogRequest;
use App\Dto\Request\ArticleUpdateRequest;
use App\Entity\Article;
use App\Entity\Catalog;
use App\Repository\ArticleRepositoryInterface;
use App\Repository\CatalogRepositoryInterface;
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
        private CatalogRepositoryInterface $catalogRepository,
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

        $article = (new Article($articleCreateRequest->getName(), $articleCreateRequest->getPrice()))
            ->setDescription($articleCreateRequest->getDescription());

        if ($image = $articleCreateRequest->getPhoto()) {
            $this->writeImage($article, $image);
        }

        $catalogSlugs = $articleCreateRequest->getCatalogs();

        if ($catalogSlugs) {
            foreach ($catalogSlugs as $slug) {
                /** @var Catalog $catalog */
                $catalog = $this->catalogRepository->findOneBy(['slug' => $slug]);
                $article->addCatalog($catalog);
            }
        }

        $this->articleRepository->store($article);

        return $article;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(ArticleUpdateRequest $updateRequest, Article $article): Article
    {
        $this->logger->info('Save article');

        $article->setName($updateRequest->getName())
            ->setDescription($updateRequest->getDescription())
            ->setPrice($updateRequest->getPrice());

        $this->articleRepository->store($article);

        return $article;
    }

    public function addToCatalogs(ArticleAddToCatalogRequest $addToCatalogRequest, Article $article): Article
    {
        $this->logger->info('Add article into catalogs');

        $catalogSlugs = $addToCatalogRequest->getCatalogs();

        if ($catalogSlugs) {
            foreach ($catalogSlugs as $slug) {
                /** @var Catalog $catalog */
                $catalog = $this->catalogRepository->findOneBy(['slug' => $slug]);
                $article->addCatalog($catalog);
            }
        }

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
        $this->writeImage($article, $image);
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

    public function removeFromCatalogs(
        ArticleRemoveFromCatalogRequest $removeFromCatalogRequest,
        Article $article
    ): Article {
        $this->logger->info('Add article into catalogs');

        $catalogSlugs = $removeFromCatalogRequest->getCatalogs();

        if ($catalogSlugs) {
            foreach ($catalogSlugs as $slug) {
                /** @var Catalog $catalog */
                $catalog = $this->catalogRepository->findOneBy(['slug' => $slug]);
                $article->removeCatalog($catalog);
            }
        }

        $this->articleRepository->store($article);

        return $article;
    }

    private function writeImage(Article $article, UploadedFile $image): void
    {
        $imageName = \sprintf('%s.%s', $article->getUuid(), $image->guessExtension());

        $this->storage->write(
            $imageName,
            $image->getContent()
        );

        $article->setPhotoFilename($imageName);
    }
}
