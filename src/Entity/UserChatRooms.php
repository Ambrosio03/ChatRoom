<?php

namespace App\Entity;

use App\Repository\UserChatRoomsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserChatRoomsRepository::class)]
class UserChatRooms
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userChatRooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userChatRooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chat $chat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user = $user_id;

        return $this;
    }

    public function getChatId(): ?Chat
    {
        return $this->chat;
    }

    public function setChatId(?Chat $chat_id): static
    {
        $this->chat = $chat_id;

        return $this;
    }
}
