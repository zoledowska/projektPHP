<?php

namespace App\Repository;

use App\Entity\Photos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class PhotosRepository.
 *
 * @method Photos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photos[]    findAll()
 * @method Photos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Photos>
 *
 * @psalm-suppress LessSpecificImplementedReturnType
 */
class PhotosRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photos::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial photos.{id, title, description, photo_path, upload_date}',
//                'partial albums.{id, name}'
            )
//            ->join('photos.albums', 'albums')
            ->orderBy('photos.upload_date', 'DESC');
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
        return $queryBuilder ?? $this->createQueryBuilder('photos');
    }
    
    /**
     * Save entity.
     *
     * @param Photos $photos Photos entity
     */
    public function save(Photos $photos): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($photos);
        $this->_em->flush();
    }
    /**
     * Delete entity.
     *
     * @param Photos $photos Photos entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Photos $photos): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->remove($photos);
        $this->_em->flush();
    }

    /**
     * Count photoss by albums.
     *
     * @param Albums $albums Albums
     *
     * @return int Number of photoss in albums
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByAlbums(Albums $albums): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('photos.id'))
            ->where('photos.albums = :albums')
            ->setParameter(':albums', $albums)
            ->getQuery()
            ->getSingleScalarResult();
    }
}