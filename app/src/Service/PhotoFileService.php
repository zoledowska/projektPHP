<?php
/**
 * PhotoFile service.
 */

namespace App\Service;

use App\Entity\PhotoFile;
use App\Repository\PhotoFileRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PhotoFileService.
 */
class PhotoFileService implements PhotoFileServiceInterface
{
    private string $targetDirectory;
    private PhotoFileRepository $photoFileRepository;
    private FileUploadServiceInterface $fileUploadService;
    private Filesystem $filesystem;

    /**
     * Constructor.
     *
     * @param string                     $targetDirectory     Target directory for photoFile uploads
     * @param PhotoFileRepository        $photoFileRepository PhotoFile repository
     * @param FileUploadServiceInterface $fileUploadService   File upload service
     * @param Filesystem                 $filesystem          Filesystem service
     */
    public function __construct(string $targetDirectory, PhotoFileRepository $photoFileRepository, FileUploadServiceInterface $fileUploadService, Filesystem $filesystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->photoFileRepository = $photoFileRepository;
        $this->fileUploadService = $fileUploadService;
        $this->filesystem = $filesystem;
    }

    /**
     * Update photoFile.
     *
     * @param UploadedFile                     $uploadedFile Uploaded file
     * @param PhotoFile                        $photoFile    PhotoFile entity
     * @param UserInterface|\App\Entity\Photos $photos       User or Photos entity
     */
    public function update(UploadedFile $uploadedFile, PhotoFile $photoFile, UserInterface|\App\Entity\Photos|PhotosServiceInterface $photos): void
    {
        $filename = $photoFile->getFilename();

        if (null !== $filename) {
            $this->filesystem->remove($this->targetDirectory.'/'.$filename);
            $this->create($uploadedFile, $photoFile, $photos);
        }
    }

    /**
     * Create photoFile.
     *
     * @param UploadedFile                              $uploadedFile Uploaded file
     * @param PhotoFile                                 $photoFile    PhotoFile entity
     * @param PhotosServiceInterface|\App\Entity\Photos $photos       Photos interface or Photos entity
     */
    public function create(UploadedFile $uploadedFile, PhotoFile $photoFile, PhotosServiceInterface|\App\Entity\Photos $photos): void
    {
        $photoFileFilename = $this->fileUploadService->upload($uploadedFile);
        $photoFile->setFilename($photoFileFilename);
        $this->photoFileRepository->save($photoFile);
    }
}
