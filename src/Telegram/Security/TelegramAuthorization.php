<?php

declare(strict_types=1);

namespace App\Telegram\Security;

use Symfony\Component\HttpFoundation\Request;

use function array_keys;
use function array_map;
use function hash;
use function hash_equals;
use function hash_hmac;
use function implode;
use function iterator_to_array;
use function sort;
use function time;

class TelegramAuthorization
{
    private string $secretKey;

    public function __construct(string $botToken)
    {
        $this->secretKey = (string) hash('sha256', $botToken, true);
    }

    public function check(Request $request): bool
    {
        if (time() - $request->get('auth_date') > 86400) {
            return false;
        }

        $receivedHash = $request->get('hash');
        $query        = iterator_to_array($request->query->getIterator());
        $hash         = $this->generateHash($query);

        return hash_equals($hash, $receivedHash);
    }

    /**
     * @param array<string,string> $receivedHash
     */
    private function generateHash(array $receivedHash): string
    {
        unset($receivedHash['hash']);
        $data = array_map(static fn ($key) => $key . '=' . $receivedHash[$key], array_keys($receivedHash));
        sort($data);
        $message = implode("\n", $data);

        return hash_hmac('sha256', $message, $this->secretKey);
    }
}
