<?php

declare(strict_types=1);

namespace App\Services\Telegram\Action;

use App\Message\UnregisterChatMessage;
use App\Services\Telegram\Processor\TelegramAction;
use App\Services\Telegram\Processor\TelegramActionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Telegram\Bot\Objects as Telegram;

class BotLeaveGroupAction extends TelegramAction
{
    private string $botUsername;
    private MessageBusInterface $messageBus;

    public function __construct(
        string $botUsername,
        MessageBusInterface $messageBus
    ) {
        $this->botUsername = $botUsername;
        $this->messageBus  = $messageBus;
    }

    protected function supports(string $type): bool
    {
        return $type === TelegramActionInterface::LEFT_CHAT_MEMBER;
    }

    protected function process(Telegram\Message $message): void
    {
        if ($this->botUsername !== $message->leftChatMember->username) {
            return;
        }

        $this->messageBus->dispatch(
            new UnregisterChatMessage(
                $message->chat->id
            )
        );
    }
}
