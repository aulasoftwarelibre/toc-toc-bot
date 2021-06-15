<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouterInterface;
use Telegram\Bot\Api;

use function sprintf;

#[AsCommand(
    name: 'bot:webhook:set',
    description: 'Add a short description for your command',
)]
class BotWebhookSetCommand extends Command
{
    private Api $telegram;
    private RouterInterface $router;
    private string $webhookSecret;

    public function __construct(Api $telegram, RouterInterface $router, string $webhookSecret, ?string $name = null)
    {
        parent::__construct($name);

        $this->telegram      = $telegram;
        $this->router        = $router;
        $this->webhookSecret = $webhookSecret;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('fqdn', InputArgument::REQUIRED, 'Set FQDN host');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io   = new SymfonyStyle($input, $output);
        $fqdn = $input->getArgument('fqdn');

        $path       = $this->router->generate('webhook', ['secret' => $this->webhookSecret]);
        $webhookUrl = sprintf('https://%s%s', $fqdn, $path);

        $this->telegram->setWebhook(['url' => $webhookUrl]);

        $io->success(sprintf('Configured webhook to: [%s]', $webhookUrl));

        return Command::SUCCESS;
    }
}
