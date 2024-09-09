<?php

namespace App\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Metadata\Operation;
use App\Entity\Product;
use Core\Repository\BaseRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class ProductRepository extends BaseRepository
{
    public const ENTITY_CLASS_NAME = Product::class;

    public function getFilteredCollection(
        iterable $apiPlatformExtensions,
        array $context,
        Operation $operation
    ): iterable {
        $qb = $this->createQueryBuilder('p');
        $qb->leftJoin('p.categories', 'c');
        $filters = $context['filters'] ?? null;

        if (null !== $filters) {
            $itemsPerPage = $filters['items_per_page'] ?? 10;
            $page = $filters['page'] ?? 1;

            if (isset($filters['category_ids'])) {
                $qb->andWhere($qb->expr()->in('c', $filters['category_ids']));
            }

            if (isset($filters['name'])) {
                $qb->andWhere('p.name LIKE :name')
                    ->setParameter('name', $filters['name']);
            }

            if (isset($filters['price'])) {
                $qb->andWhere('p.price = :price')
                    ->setParameter('price', $filters['price']);
            }

            if (isset($filters['order_by'])) {
                $direction = $filters['order_direction'] ?? 'ASC';
                switch ($filters['order_by']) {
                    case 'category':
                        $qb->orderBy('c.name', $direction);
                        $qb->addOrderBy('p.id', $direction);
                        break;
                    default:
                        $qb->orderBy('p.'.$filters['order_by'], $direction);
                }
            }

            $qb->setFirstResult(($page - 1) * $itemsPerPage)
                ->setMaxResults($itemsPerPage);
        }

        $dp = new DoctrinePaginator($qb);

        return new Paginator($dp);
    }
}
