<?php

declare(strict_types=1);

namespace App\Services\Telegram\Processor;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Telegram\Bot\Objects as Telegram;

class TelegramActionsProcessor
{
    /** @var TelegramActionInterface[] */
    private iterable $actions;

    /**
     * @param TelegramActionInterface[] $actions
     */
    public function __construct(
        #[TaggedIterator('telegram.message_action')] iterable $actions,
    ) {
        $this->actions = $actions;
    }

    public function dispatch(Telegram\Message $message): void
    {
        foreach ($this->actions as $action) {
            $action->handle($message);
        }
    }
}
