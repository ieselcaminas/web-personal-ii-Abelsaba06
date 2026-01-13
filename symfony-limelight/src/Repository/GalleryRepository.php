<?php

namespace App\Repository;

use App\Entity\Gallery;
use App\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Gallery>
 */
class GalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gallery::class);
    }
    public function findAllPaginated(int $page): Paginator
    {
        $qb =  $this->createQueryBuilder('p')->orderBy('p.id', 'ASC');
        return (new Paginator($qb))->paginate($page);
    }
    public function findByText(string $searchTerm): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere("p.name LIKE :val")
            ->setParameter('val', '%'.$searchTerm.'%')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();

    }


    //    /**
    //     * @return Gallery[] Returns an array of Gallery objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Gallery
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
