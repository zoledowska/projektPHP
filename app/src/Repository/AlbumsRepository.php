<?php

namespace App\Repository;

use App\Entity\Albums;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @extends ServiceEntityRepository<Albums>
 *
 * @method Albums|null find($id, $lockMode = null, $lockVersion = null)
 * @method Albums|null findOneBy(array $criteria, array $orderBy = null)
 * @method Albums[]    findAll()
 * @method Albums[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Albums::class);
    }

//    /**
//     * @return Albums[] Returns an array of Albums objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Albums
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


/**
 * Query all records.
 *
 * @return QueryBuilder Query builder
 */
public function queryAll(): QueryBuilder
{
    return $this->getOrCreateQueryBuilder()
        ->select('albums', 'partial photos.{id}')
        ->join('albums.photos', 'photos')
        ->orderBy('albums.name', 'DESC');
}

/**
 * Get or create new query builder.
 *
 * @param QueryBuilder|null $queryBuilder Query builder
 *
 * @return QueryBuilder Query builder
 */
private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
{
    return $queryBuilder ?? $this->createQueryBuilder('albums');
}
    /**
     * Save entity.
     *
     * @param Albums $albums Albums entity
     */
    public function save(Albums $albums): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($albums);
        $this->_em->flush();
    }
    /**
     * Delete entity.
     *
     * @param Albums $albums Albums entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Albums $albums): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->remove($albums);
        $this->_em->flush();
    }
}
