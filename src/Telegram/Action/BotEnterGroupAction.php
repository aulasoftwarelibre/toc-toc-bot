<?php

declare(strict_types=1);

namespace App\Telegram\Action;

use App\Message\RegisterChatMessage;
use App\Telegram\Processor\TelegramAction;
use App\Telegram\Processor\TelegramActionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Telegram\Bot\Objects as Telegram;

class BotEnterGroupAction extends TelegramAction
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
        return $type === TelegramActionInterface::NEW_CHAT_MEMBER;
    }

    protected function process(Telegram\Message $message): void
    {
        $newChatMembers = $message->newChatMembers;

        $found = false;
        foreach ($newChatMembers as $newChatMember) {
            if ($newChatMember['username'] === $this->botUsername) {
                $found = true;
                break;
            }
        }

        if (! $found) {
            return;
        }

        $this->messageBus->dispatch(
            new RegisterChatMessage(
                $message->chat->id,
                $message->chat->type,
                $message->chat->title
            )
        );
    }
}
