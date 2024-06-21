<?php
/**
 * Albums service.
 */

namespace App\Service;

use App\Entity\Albums;
use App\Repository\AlbumsRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AlbumsService.
 */
class AlbumsService implements AlbumsServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param AlbumsRepository     $albumsRepository Albums repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(private readonly AlbumsRepository $albumsRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->albumsRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }
    /**
     * Save entity.
     *
     * @param Albums $albums Albums entity
     */
    public function save(Albums $albums): void
    {
        if (null == $albums->getId()) {
            $albums->setCreatedAt(new \DateTimeImmutable());
        }
        $albums->setCreatedAt(new \DateTime());

        $this->albumsRepository->save($albums);
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
    /**
     * Can Albums be deleted?
     *
     * @param Albums $albums Albums entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Albums $albums): bool
    {
        try {
            $result = $this->taskRepository->countByAlbums($albums);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }
}
