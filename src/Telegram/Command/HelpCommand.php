<?php

declare(strict_types=1);

namespace App\Telegram\Command;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

use function sprintf;

use const PHP_EOL;

class HelpCommand extends Command
{
    public function __construct()
    {
        $this
            ->setName('help')
            ->setDescription('Get a list of commands');
    }

    public function handle(): void
    {
        $this->replyWithMessage(['text' => 'Hello! Welcome to our bot, Here are our available commands:']);

        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $commands = $this->getTelegram()->getCommands();

        // Build the list
        $response = '';
        foreach ($commands as $name => $command) {
            $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
        }

        // Reply with the commands list
        $this->replyWithMessage(['text' => $response]);
    }
}
