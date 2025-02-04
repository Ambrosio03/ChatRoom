<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'chat', orphanRemoval: true)]
    private Collection $messages;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'chat')]
    private Collection $users;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->userChatRooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setChat($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getChat() === $this) {
                $message->setChat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @var Collection<int, UserChatRooms>
     */
    #[ORM\OneToMany(targetEntity: UserChatRooms::class, mappedBy: 'chat_id')]
    private Collection $userChatRooms;


    /**
     * @return Collection<int, UserChatRooms>
     */
    public function getUserChatRooms(): Collection
    {
        return $this->userChatRooms;
    }

    public function addUserChatRoom(UserChatRooms $userChatRoom): static
    {
        if (!$this->userChatRooms->contains($userChatRoom)) {
            $this->userChatRooms->add($userChatRoom);
            $userChatRoom->setChatId($this);
        }

        return $this;
    }

    public function removeUserChatRoom(UserChatRooms $userChatRoom): static
    {
        if ($this->userChatRooms->removeElement($userChatRoom)) {
            // set the owning side to null (unless already changed)
            if ($userChatRoom->getChatId() === $this) {
                $userChatRoom->setChatId(null);
            }
        }

        return $this;
    }

}
