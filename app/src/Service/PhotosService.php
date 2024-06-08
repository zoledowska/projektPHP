<?php
/**
 * Photos service.
 */

namespace App\Service;

use App\Entity\Photos;
use App\Repository\PhotosRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PhotosService.
 */
class PhotosService implements PhotosServiceInterface
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
     * @param PhotosRepository     $photosRepository Photos repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(private readonly PhotosRepository $photosRepository, private readonly PaginatorInterface $paginator)
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
            $this->photosRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Photos|Photos $photos Photos entity
     */
    public function save(Photos|\App\Service\Photos $photos): void
    {
        if (null == $photos->getId()) {
            $photos->setupload_date(new \DateTimeImmutable());
        }

        $this->photosRepository->save($photos);
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

}