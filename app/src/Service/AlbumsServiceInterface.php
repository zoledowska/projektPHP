<?php
/**
 * Albums service interface.
 */

namespace App\Service;

use App\Entity\Albums;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface AlbumsServiceInterface.
 */
interface AlbumsServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;
    

    /**
     * Save entity.
     *
     * @param Albums $albums Albums entity
     */
    public function save(Albums $albums): void;

    /**
     * Can Albums be deleted?
     *
     * @param Albums $albums Albums entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Albums $albums): bool;
}
