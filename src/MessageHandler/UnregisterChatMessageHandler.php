<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Chat;
use App\Message\UnregisterChatMessage;
use App\Repository\ChatRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UnregisterChatMessageHandler implements MessageHandlerInterface
{
    private ChatRepository $chatRepository;

    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    public function __invoke(UnregisterChatMessage $message): void
    {
        $chat = $this->chatRepository->find($message->getChatId());

        if (! $chat instanceof Chat) {
            return;
        }

        $this->chatRepository->remove($chat);
    }
}
