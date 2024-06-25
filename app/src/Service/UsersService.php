<?php
/**
 * Users service.
 */

namespace App\Service;

use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Repository\PhotosRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UsersService.
 */
class UsersService implements UsersServiceInterface
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

    private UsersRepository $usersRepository;

    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param PaginatorInterface $paginator Paginator
     */
    public function __construct(PhotosRepository $photosRepository, PaginatorInterface $paginator, UsersRepository $usersRepository)
    {
        $this->photosRepository = $photosRepository;
        $this->paginator = $paginator;
        $this->usersRepository = $usersRepository;
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
            $this->usersRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Users $users Users entity
     */
    public function save(Users $users): void
    {
        $this->usersRepository->save($users);
    }

    /**
     * Delete entity.
     *
     * @param Users $users Users entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Users $users): void
    {
        $this->usersRepository->delete($users);
    }

    /**
     * Can Users be deleted?
     *
     * @param Users $users Users entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Users $users): bool
    {
        try {
            $result = $this->photosRepository->queryByAuthor($users);
            $count = $result->getQuery()->getSingleScalarResult();
            return $count == 0;
        }
        catch (NoResultException) {
            return true;
        }
        catch(NonUniqueResultException) {
            return false;
        }
    }
}
