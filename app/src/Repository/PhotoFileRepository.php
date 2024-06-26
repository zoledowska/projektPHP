<?php
/**
 * Photo file repository.
 */

namespace App\Repository;

use App\Entity\PhotoFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PhotoFile>
 *
 * @method PhotoFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhotoFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhotoFile[]    findAll()
 * @method PhotoFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoFileRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotoFile::class);
    }

    /**
     * Save entity.
     *
     * @param PhotoFile $photoFile PhotoFile entity
     */
    public function save(PhotoFile $photoFile): void
    {
        $this->_em->persist($photoFile);
        $this->_em->flush();
    }
}

// Ensure there is a newline at the end of the file
