<?php

namespace App\Repository;

use App\Entity\Warehouse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use http\Client\Curl\User;
use http\QueryString;
use phpDocumentor\Reflection\Types\True_;
use PHPUnit\Util\Json;
use Symfony\Component\Form\Form;

/**
 * @extends ServiceEntityRepository<Warehouse>
 *
 * @method Warehouse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Warehouse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Warehouse[]    findAll()
 * @method Warehouse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Warehouse::class);
    }

    public function save(Warehouse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Warehouse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findUserWarehouses(int $id): array
    {

        $dql = 'SELECT w FROM App\Entity\Warehouse w JOIN w.users u WHERE u.id = :id';

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('id', $id);
        return $query->execute();
    }

    public function getWerhouses()
    {
        $qb = $this->createQueryBuilder('w');
        $qb->select('w')
            ->from('App:Warehouse', 'warehouse_alias');

        return $qb;
    }

    public function getWarehousesByUserId(int $userId) : QueryBuilder
    {
        $qb = $this->createQueryBuilder('w');
        $qb ->join('w.users', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $userId);

        return $qb;
    }

//    /**
//     * @return Warehouse[] Returns an array of Warehouse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Warehouse
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
