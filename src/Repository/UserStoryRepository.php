<?php

namespace App\Repository;

use App\Entity\UserStory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserStory|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserStory|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserStory[]    findAll()
 * @method UserStory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserStoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserStory::class);
    }

    public function findLimitedUserStories($user, $offset = 0)
    {
        return $this->createQueryBuilder('u')
            ->join('u.story', 's')
            ->andWhere('u.user = :user')
            ->setParameter('user', $user)
            ->orderBy('s.date', 'DESC')
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countUnreadUserstoriesByFeed($user)
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u) as unreadCount', 'u.feed')
            ->groupBy('c.feed')
            ->andWhere('u.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return UserStory[] Returns an array of UserStory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserStory
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
