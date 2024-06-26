<?php
/**
 * Users service Interface.
 */

namespace App\Service;

use App\Entity\Users;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface UsersServiceInterface.
 *
 * Define methods to be implemented by UsersService.
 */
interface UsersServiceInterface
{
    /**
     * Get paginated list of users.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<PaginationInterface<string, mixed>> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save a Users entity.
     *
     * @param Users $users Users entity
     */
    public function save(Users $users): void;

    /**
     * Delete a Users entity.
     *
     * @param Users $users Users entity
     */
    public function delete(Users $users): void;
}

// End of UsersServiceInterface.php file
