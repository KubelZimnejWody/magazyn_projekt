<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 *
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function save(Item $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Item $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getItems()
    {
        $qb = $this->createQueryBuilder('i');
        $qb->select('i')
            ->from('App:Item', 'item_alias');

        return $qb;
    }

    public function getItemsByWarehouseId(int $warehouseId) : QueryBuilder
    {
        $qb = $this->createQueryBuilder('i');
        $qb ->from('App:Item', 'alias')
//            ->join('i.warehouse', 'w')
            ->where('i.warehouse = :warehouse')
            ->setParameter('warehouse', $warehouseId);

        return $qb;
    }

    public function getItemsNotInWarehouse(int $warehouseId): QueryBuilder
    {
        $qb = $this->createQueryBuilder('i');
        $qb->select('i')
            ->leftJoin('i.warehouses', 'w')
            ->where($qb->expr()->neq('w.warehouse', ':warehouseId'))
            ->orWhere($qb->expr()->isNull('w.warehouse'))
            ->setParameter('warehouseId', $warehouseId);

        return $qb;
    }
//    public function finditemsByWarehouseID(int $id): array
//    {
//
////        $dql = 'SELECT i FROM App\Entity\Item i WHERE i.warehouse_id = :id';
////
////        $query = $this->getEntityManager()->createQuery($dql);
////        $query->setParameter('id', $id);
////        return $query->execute();
//    }
//    /**
//     * @return Item[] Returns an array of Item objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Item
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
