<?php
/**
 * PhotoFile service interface.
 */

namespace App\Service;

use App\Entity\PhotoFile;
use App\Entity\Photos;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface PhotoFileServiceInterface.
 */
interface PhotoFileServiceInterface
{
    /**
     * Create photoFile.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param PhotoFile    $photoFile    PhotoFile entity
     * @param Photos       $photos       Photos entity
     */
    public function create(UploadedFile $uploadedFile, PhotoFile $photoFile, Photos $photos): void;

    /**
     * Update photoFile.
     *
     * @param UploadedFile           $uploadedFile Uploaded file
     * @param PhotoFile              $photoFile    PhotoFile entity
     * @param PhotosServiceInterface $photos       Photos interface
     */
    public function update(UploadedFile $uploadedFile, PhotoFile $photoFile, PhotosServiceInterface $photos): void;
}
