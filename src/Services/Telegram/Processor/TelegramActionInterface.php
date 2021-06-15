<?php

declare(strict_types=1);

namespace App\Services\Telegram\Processor;

use Telegram\Bot\Objects as Telegram;

interface TelegramActionInterface
{
    public const NEW_CHAT_MEMBER  = 'new_chat_member';
    public const LEFT_CHAT_MEMBER = 'left_chat_member';
    public const TEXT             = 'text';

    public function handle(Telegram\Message $message): void;
}
