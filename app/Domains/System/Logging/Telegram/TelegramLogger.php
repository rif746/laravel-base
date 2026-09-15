<?php

namespace App\Domains\System\Logging\Telegram;

use Monolog\Logger;

class TelegramLogger
{
    /**
     * Create a custom Monolog instance for Telegram logging.
     *
     * @param array<string, mixed> $config
     * @return Logger
     */
    public function __invoke(array $config): Logger
    {
        $handler = new TelegramHandler(
            botToken: $config['token'] ?? '',
            chatId: $config['chat_id'] ?? '',
            level: $config['level'] ?? 'error'
        );

        return new Logger('telegram', [$handler]);
    }
}
