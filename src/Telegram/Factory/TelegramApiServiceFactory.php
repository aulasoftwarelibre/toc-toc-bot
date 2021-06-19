<?php

declare(strict_types=1);

namespace App\Telegram\Factory;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;

class TelegramApiServiceFactory
{
    /** @var Command[] */
    private iterable $commands;
    private string $botToken;

    /**
     * @param Command[] $commands
     */
    public function __construct(
        #[TaggedIterator('telegram.command')] iterable $commands,
        string $botToken
    ) {
        $this->commands = $commands;
        $this->botToken = $botToken;
    }

    public function __invoke(): Api
    {
        $telegram = new Api($this->botToken, false);

        foreach ($this->commands as $command) {
            $telegram->addCommand($command);
        }

        $telegram->commandsHandler(true);

        return $telegram;
    }
}
