<?php

declare(strict_types=1);

namespace App\Services\Telegram\Processor;

use Telegram\Bot\Objects as Telegram;

abstract class TelegramAction implements TelegramActionInterface
{
    public function handle(Telegram\Message $message): void
    {
        if (! $this->supports($message->detectType())) {
            return;
        }

        $this->process($message);
    }

    abstract protected function supports(string $type): bool;

    abstract protected function process(Telegram\Message $message): void;
}
