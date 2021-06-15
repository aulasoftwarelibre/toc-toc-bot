<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\Telegram\Processor\TelegramActionsProcessor;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;
use Throwable;
use TypeError;

use function dump;
use function Symfony\Component\String\u;

class WebhookController extends AbstractController
{
    private Api $telegram;
    private LoggerInterface $logger;
    private TelegramActionsProcessor $telegramActionsProcessor;
    private string $webhookSecret;

    public function __construct(Api $telegram, LoggerInterface $logger, TelegramActionsProcessor $telegramActionsProcessor, string $webhookSecret)
    {
        $this->telegram                 = $telegram;
        $this->logger                   = $logger;
        $this->telegramActionsProcessor = $telegramActionsProcessor;
        $this->webhookSecret            = $webhookSecret;
    }

    #[Route('/webhook/{secret}', name: 'webhook', methods: ['POST'])]
    public function index(string $secret): Response
    {
        if (! u($secret)->equalsTo($this->webhookSecret)) {
            $this->createNotFoundException();
        }

        try {
            $update  = $this->telegram->getWebhookUpdate();
            $message = $update->getMessage();

            if ($message instanceof Message) {
                $this->telegramActionsProcessor->dispatch($message);
            }
        } catch (Throwable | TypeError $e) {
            $this->logger->error($e->getMessage());
            dump($e);
        }

        return new Response();
    }
}
