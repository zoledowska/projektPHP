<?php
/**
 * Photos service Interface.
 */

namespace App\Service;

use App\Entity\Photos;
use App\Entity\Albums;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface PhotosServiceInterface.
 *
 * Interface defining methods for managing photos.
 */
interface PhotosServiceInterface
{
    /**
     * Get paginated list of photos.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<PaginationInterface<string, mixed>> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Get paginated list of photos by album.
     *
     * @param int    $page   Page number
     * @param Albums $albums Album entity
     *
     * @return PaginationInterface<PaginationInterface<string, mixed>> Paginated list
     */
    public function getPhotosByAlbum(int $page, Albums $albums): PaginationInterface;

    /**
     * Save a photo entity.
     *
     * @param Photos $photo Photo entity to save
     */
    public function save(Photos $photo): void;

    /**
     * Delete a photo entity.
     *
     * @param Photos $photo Photo entity to delete
     */
    public function delete(Photos $photo): void;
}
