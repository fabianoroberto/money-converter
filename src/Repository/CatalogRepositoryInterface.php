<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Catalog;
use App\Repository\Common\PaginatorInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ObjectRepository;

/**
 * @method Catalog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Catalog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Catalog[]    findAll()
 * @method Catalog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface CatalogRepositoryInterface extends PaginatorInterface, ObjectRepository
{
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function store(Catalog $catalog);

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Catalog $catalog);
}
