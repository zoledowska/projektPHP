<?php
/**
 * Comments service interface.
 */

namespace App\Service;

use App\Entity\Photos;
use App\Entity\Users;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CommentsServiceInterface.
 */
interface CommentsServiceInterface
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
     * Get paginated list by photos.
     *
     * @param int     $page    Page
     * @param Photos $photos Photos
     *
     * @return PaginationInterface Paginator interface
     */
    public function getPaginatedListByPhotos(int $page, Photos $photos): PaginationInterface;

    /**
     * Get paginated list by user.
     *
     * @param int  $page Page
     * @param Users $user User
     *
     * @return PaginationInterface Paginator interface
     */
    public function getPaginatedListByUser(int $page, Users $user): PaginationInterface;
}