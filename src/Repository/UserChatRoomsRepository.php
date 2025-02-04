<?php

namespace App\Repository;

use App\Entity\Chat;
use App\Entity\User;
use App\Entity\UserChatRooms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserChatRooms>
 */
class UserChatRoomsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserChatRooms::class);
    }

    public function findByUserId ($userId): array
    {
        return  $this->createQueryBuilder('u')
            ->andWhere('u.user = :val')
            ->setParameter('val', $userId)
            ->getQuery()
            ->getResult();
        
    }

    public function findByChatId ($chatId): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.chat = :val')
            ->setParameter('val', $chatId)
            ->getQuery()
            ->getResult();
    }

    public function findByUserIdAndChatId ($userId, $chatId): ?UserChatRooms
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.user = :userId')
            ->andWhere('u.chat = :chatId')
            ->setParameter('userId', $userId)
            ->setParameter('chatId', $chatId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findGroupByChatId (): array
    {
        return $this->createQueryBuilder('u')
            ->groupBy('u.chat')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return UserChatRooms[] Returns an array of UserChatRooms objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserChatRooms
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
