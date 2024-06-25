<?php

/**
 * Comments repository.
 */

namespace App\Repository;

use App\Entity\Comments;
use App\Entity\Photos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comments>
 *
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Comments::class);
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
                'partial comments.{id, post_date, content}',
                'partial photos.{id, title}'
            )
            ->join('comments.photos', 'photos')
            ->orderBy('comments.post_date', 'DESC');
    }

    /**
     * Count comments by photos.
     *
     * @param Photos $photos Photos
     *
     * @return int Number of comments in photos
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByPhotos(Photos $photos): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('comments.id'))
            ->where('comments.photos = :photos')
            ->setParameter(':photos', $photos)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Save entity.
     *
     * @param Comments $comments Comments entity
     */
    public function save(Comments $comments): void
    {
        $this->_em->persist($comments);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Comments $comments Comments entity
     */
    public function delete(Comments $comments): void
    {
        $this->_em->remove($comments);
        $this->_em->flush();
    }

    /**
     * Removing comments.
     *
     * @param Comments $entity Comments entity
     * @param bool     $flush  Flush
     */
    public function remove(Comments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(?QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('comments');
    }
}
