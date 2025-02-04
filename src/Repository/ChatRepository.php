<?php

namespace App\Repository;

use App\Entity\Chat;
use App\Repository\UserChatRoomsRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Chat>
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    public function findAllActiveChats(UserChatRoomsRepository $userChatRoomsRepository): array
    {
        $userChatsRooms = $userChatRoomsRepository->findGroupByChatId();
        $activeChats = [];
        foreach ($userChatsRooms as $userChatRoom) {
            $activeChats[] = $userChatRoom->getChatId();
        }
        return $activeChats;
    }

    public function findAllMyChats(UserChatRoomsRepository $userChatRoomsRepository, $userId): array
    {
        $userChatsRooms = $userChatRoomsRepository->findByUserId($userId);
        $activeChats = [];
        foreach ($userChatsRooms as $userChatRoom) {
            $activeChats[] = $userChatRoom->getChatId();
        }
        return $activeChats;
    }

}
