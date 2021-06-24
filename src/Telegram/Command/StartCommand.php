<?php

declare(strict_types=1);

namespace App\Telegram\Command;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    public function __construct()
    {
        $this
            ->setName('start')
            ->setDescription('Start command to get you started');
    }

    public function handle(): void
    {
    }
}
