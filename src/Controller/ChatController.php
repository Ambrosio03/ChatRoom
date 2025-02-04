<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Form\ChatType;
use App\Repository\ChatRepository;
use App\Repository\UserChatRoomsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chat')]
final class ChatController extends AbstractController
{
     #[Route(name: 'app_chat_index', methods: ['GET'])]
    public function index(ChatRepository $chatRepository, UserChatRoomsRepository $userChatRoomsRepository, Security $security, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(["username" => $security->getUser()->getUserIdentifier()]);
        return $this->render('chat/index.html.twig', [
            'chats' => $chatRepository->findAllActiveChats($userChatRoomsRepository),
            'myChats' => $chatRepository->findAllMyChats($userChatRoomsRepository, $user->getId()),
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'app_chat_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['username' => $this->getUser()->getUserIdentifier()]);
        $chat = new Chat();
        $entityManager->persist($chat);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_chat_rooms_new', ['chat_id' => $chat->getId(), 'user_id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_chat_show', methods: ['GET'])]
    public function show(Chat $chat, Security $security, UserRepository $userRepository, UserChatRoomsRepository $userChatRoomsRepository): Response
    {
        $user = $userRepository->findOneBy(['username' => $security->getUser()->getUserIdentifier()]);
        $usersActive = count($userChatRoomsRepository->findByChatId($chat->getId()));
        
        return $this->render('chat/show.html.twig', [
            'usersActive' => $usersActive,
            'chat' => $chat,
            'messages' => $chat->getMessages(),
            'user' => $user,
        ]);
    }
}
