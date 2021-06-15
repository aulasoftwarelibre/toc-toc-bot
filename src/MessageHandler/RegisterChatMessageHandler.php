<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Chat;
use App\Message\RegisterChatMessage;
use App\Repository\ChatRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RegisterChatMessageHandler implements MessageHandlerInterface
{
    private ChatRepository $chatRepository;

    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    public function __invoke(RegisterChatMessage $message): void
    {
        if ($this->chatRepository->find($message->getChatId()) instanceof Chat) {
            return;
        }

        $chat = new Chat();
        $chat->setId($message->getChatId());
        $chat->setType($message->getType());
        $chat->setTitle($message->getTitle());

        $this->chatRepository->save($chat);
    }
}
