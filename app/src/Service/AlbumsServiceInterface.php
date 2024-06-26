<?php
/**
 * Albums service interface.
 */

namespace App\Service;

use App\Entity\Albums;
use Doctrine\ORM\OptimisticLockException;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface AlbumsServiceInterface.
 */
interface AlbumsServiceInterface
{
    /**
     * Get paginated list of albums.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<PaginationInterface<string, mixed>> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save an album entity.
     *
     * @param Albums $album Album entity to save
     */
    public function save(Albums $album): void;

    /**
     * Delete an album entity.
     *
     * @param Albums $album Album entity to delete
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Albums $album): void;

    /**
     * Check if an album can be deleted.
     *
     * @param Albums $album Album entity to check
     *
     * @return bool Whether the album can be deleted
     */
    public function canBeDeleted(Albums $album): bool;
}
