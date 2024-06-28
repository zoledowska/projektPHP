<?php
/**
 * Albums service.
 */

namespace App\Service;

use App\Entity\Albums;
use App\Repository\AlbumsRepository;
use App\Repository\PhotosRepository;
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

    private PhotosRepository $photosRepository;
    private AlbumsRepository $albumsRepository;
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param PhotosRepository   $photosRepository Photos repository
     * @param PaginatorInterface $paginator        Paginator
     * @param AlbumsRepository   $albumsRepository Albums repository
     */
    public function __construct(PhotosRepository $photosRepository, PaginatorInterface $paginator, AlbumsRepository $albumsRepository)
    {
        $this->photosRepository = $photosRepository;
        $this->paginator = $paginator;
        $this->albumsRepository = $albumsRepository;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<PaginationInterface<string, mixed>> Paginated list
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
        if (null === $albums->getId()) {
            $albums->setCreatedAt(new \DateTimeImmutable());
        }
        $albums->setCreatedAt(new \DateTimeImmutable());

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
        $this->albumsRepository->delete($albums);
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
        return !$this->photosRepository->findBy(['album' => $albums]);
    }
}

// End of AlbumsService.php file
