<?php

namespace App\Repository;

use App\Entity\GroupMeMessageImageAttachment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupMeMessageImageAttachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupMeMessageImageAttachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupMeMessageImageAttachment[]    findAll()
 * @method GroupMeMessageImageAttachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupMeMessageImageAttachmentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupMeMessageImageAttachment::class);
    }

    // /**
    //  * @return GroupMeMessageImageAttachment[] Returns an array of GroupMeMessageImageAttachment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupMeMessageImageAttachment
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
