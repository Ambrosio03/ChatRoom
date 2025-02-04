<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/message')]
final class MessageController extends AbstractController{
    #[Route(name: 'app_message_index', methods: ['GET','POST'])]
    public function index(MessageRepository $messageRepository): Response
    {
        
        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Chat $chat, Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $message->setUser($this->getUser());
        $message->setChat($chat);
        $message->setDate(new \DateTime());
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_chat_show', ['id' => $chat->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('message/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
