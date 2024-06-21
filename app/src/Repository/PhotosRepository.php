<?php

namespace App\Repository;

use App\Entity\Albums;
use App\Entity\Photos;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
                'partial photos.{id, title, description, photoFile, upload_date}',
                'partial album.{id, title, description, created_at}'
            )
            ->join('photos.album', 'album')
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
     * Count photoss by album.
     *
     * @param Albums $album Albums
     *
     * @return int Number of photos in album
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByAlbums(Albums $album): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('photos.id'))
            ->where('photos.album = :album')
            ->setParameter(':album', $album)
            ->getQuery()
            ->getSingleScalarResult();
    }
    /**
     * Query photoss by author.
     *
     * @param Users $user Users entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByAuthor(Users $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('photos.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }
}