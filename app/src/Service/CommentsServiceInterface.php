<?php
/**
 * Comments service Interface.
 */

namespace App\Service;

use App\Entity\Photos;
use App\Entity\Users;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CommentsServiceInterface.
 *
 * Define methods to be implemented by CommentsService.
 */
interface CommentsServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<PaginationInterface<string, mixed>> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Get paginated list by photos.
     *
     * @param int    $page   Page number
     * @param Photos $photos Photos entity
     *
     * @return PaginationInterface<PaginationInterface<string, mixed>> Paginated list
     */
    public function getPaginatedListByPhotos(int $page, Photos $photos): PaginationInterface;

    /**
     * Get paginated list by user.
     *
     * @param int   $page Page number
     * @param Users $user User entity
     *
     * @return PaginationInterface<PaginationInterface<string, mixed>> Paginated list
     */
    public function getPaginatedListByUser(int $page, Users $user): PaginationInterface;
}

// End of CommentsServiceInterface.php file
