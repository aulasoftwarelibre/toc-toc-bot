<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Telegram\Bot\Api;

#[AsCommand(
    name: 'bot:me',
    description: 'Add a short description for your command',
)]
class BotMeCommand extends Command
{
    private Api $telegram;

    public function __construct(Api $telegram, ?string $name = null)
    {
        parent::__construct($name);

        $this->telegram = $telegram;
    }

    protected function configure(): void
    {
        $this
            ->getDescription('Returns bot information');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $response = $this->telegram->getMe();

        $io->table(
            ['id', 'firstname', 'username'],
            [
                [$response->id, $response->firstName, $response->username],
            ]
        );

        return Command::SUCCESS;
    }
}
