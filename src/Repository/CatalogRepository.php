<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Catalog;
use App\Repository\Traits\HateoasRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Catalog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Catalog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Catalog[]    findAll()
 * @method Catalog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatalogRepository extends ServiceEntityRepository implements CatalogRepositoryInterface
{
    use HateoasRepositoryTrait;

    protected string $alias = 'c';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Catalog::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function store(Catalog $catalog)
    {
        $this->_em->persist($catalog);
        $this->_em->flush();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Catalog $catalog)
    {
        $this->_em->remove($catalog);
        $this->_em->flush();
    }
}
