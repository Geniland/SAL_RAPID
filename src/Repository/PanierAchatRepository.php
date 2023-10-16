<?php

namespace App\Repository;

use App\Entity\PanierAchat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PanierAchat>
 *
 * @method PanierAchat|null find($id, $lockMode = null, $lockVersion = null)
 * @method PanierAchat|null findOneBy(array $criteria, array $orderBy = null)
 * @method PanierAchat[]    findAll()
 * @method PanierAchat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierAchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PanierAchat::class);
    }

//    /**
//     * @return PanierAchat[] Returns an array of PanierAchat objects
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

//    public function findOneBySomeField($value): ?PanierAchat
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
