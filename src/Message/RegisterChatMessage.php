<?php

declare(strict_types=1);

namespace App\Message;

final class RegisterChatMessage
{
    private int $chatId;
    private string $type;
    private ?string $title;

    public function __construct(int $chatId, string $type, ?string $title)
    {
        $this->chatId = $chatId;
        $this->type   = $type;
        $this->title  = $title;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
