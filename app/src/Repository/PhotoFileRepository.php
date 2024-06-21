<?php

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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotoFile::class);
    }

//    /**
//     * @return PhotoFile[] Returns an array of PhotoFile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PhotoFile
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function save(PhotoFile $photoFile): void
    {
        $this->_em->persist($photoFile);
        $this->_em->flush();
    }
}
