<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Request\CatalogCreateRequest;
use App\Dto\Request\CatalogUpdateRequest;
use App\Entity\Catalog;
use App\Repository\CatalogRepositoryInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

class CatalogService
{
    public function __construct(
        private CatalogRepositoryInterface $catalogRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function store(CatalogCreateRequest $catalogCreateRequest): Catalog
    {
        $this->logger->info('Create catalog');

        $catalog = (new Catalog($catalogCreateRequest->getName()))
            ->setDescription($catalogCreateRequest->getDescription());

        $this->catalogRepository->store($catalog);

        return $catalog;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(CatalogUpdateRequest $catalogUpdateRequest, Catalog $catalog): Catalog
    {
        $this->logger->info('Save catalog');

        $catalog->setName($catalogUpdateRequest->getName())
            ->setDescription($catalogUpdateRequest->getDescription());

        $this->catalogRepository->store($catalog);

        return $catalog;
    }

    public function delete(Catalog $catalog): bool
    {
        try {
            $this->catalogRepository->delete($catalog);

            return true;
        } catch (ORMException $e) {
            $this->logger->error($e->getMessage());

            return false;
        }
    }
}
