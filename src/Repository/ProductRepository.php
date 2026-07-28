<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[]
     */
    public function findActive(?string $categorieSlug = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.actif = true')
            ->orderBy('p.nom', 'ASC');

        if (null !== $categorieSlug) {
            $qb->join('p.categorie', 'c')
                ->andWhere('c.slug = :slug')
                ->setParameter('slug', $categorieSlug);
        }

        return $qb->getQuery()->getResult();
    }
}
