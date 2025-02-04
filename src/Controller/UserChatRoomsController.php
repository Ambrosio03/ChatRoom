<?php

namespace App\Controller;

use App\Entity\UserChatRooms;
use App\Form\UserChatRoomsType;
use App\Repository\ChatRepository;
use App\Repository\UserChatRoomsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user/chat/rooms')]
final class UserChatRoomsController extends AbstractController
{
    #[Route('/new', name: 'app_user_chat_rooms_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, ChatRepository $chatRepository, UserChatRoomsRepository $userChatRoomsRepository): Response
    {
        $user_id = $request->query->get('user_id');
        $user = $userRepository->find($user_id);
        $chat_id = $request->query->get('chat_id');
        $chat = $chatRepository->find($chat_id);

        if ($userChatRoomsRepository->findByUserIdAndChatId($user_id, $chat_id) == null) {
            $userChatRoom = new UserChatRooms();
            $userChatRoom->setUserId($user);
            $userChatRoom->setChatId($chat);
            $entityManager->persist($userChatRoom);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chat_show', ["id" => $chat_id], Response::HTTP_SEE_OTHER);
        
    }

    #[Route('/delete', name: 'app_user_chat_rooms_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager, UserChatRoomsRepository $userChatRoomsRepository): Response
    {
        $userChatRoom = $userChatRoomsRepository->findByUserIdAndChatId($request->request->get('user_id'), $request->request->get('chat_id'));

        $entityManager->remove($userChatRoom);
        $entityManager->flush();

        return $this->redirectToRoute('app_chat_index', [], Response::HTTP_SEE_OTHER);
    }
}
