<?php

namespace App\Domains\System\Logging\Telegram;

use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Queries\GetSystemSettings;
use Illuminate\Support\Facades\Http;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Throwable;

class TelegramHandler extends AbstractProcessingHandler
{
    /**
     * Create a new Telegram Log Handler instance.
     *
     * @param string $botToken
     * @param string $chatId
     * @param int|string|Level $level
     * @param bool $bubble
     */
    public function __construct(
        protected string $botToken,
        protected string $chatId,
        int|string|Level $level = Level::Error,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);
    }

    /**
     * Write the log record to Telegram synchronously with fail-safe network configurations.
     *
     * @param LogRecord $record
     * @return void
     */
    protected function write(LogRecord $record): void
    {
        // Skip execution if credentials are empty
        if (empty($this->botToken) || empty($this->chatId)) {
            return;
        }

        try {
            $formattedText = $this->formatTelegramMessage($record);

            // Execute POST request with resilient cURL TLS options to prevent connection resets
            $response = Http::timeout(5)
                ->connectTimeout(3)
                ->async()
                ->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                    'chat_id' => $this->chatId,
                    'text' => $formattedText,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]);

            // Debug failure logs on non-production environments
            if ($response->failed() && config('app.env') !== 'production') {
                logger()->channel('single')->warning('Telegram Logger API Failure:', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
            }

        } catch (Throwable $e) {
            // Fail silently on production, but output debug trace in storage/logs/laravel.log during development
            if (config('app.env') !== 'production') {
                logger()->channel('single')->warning('Telegram Logger cURL Connection Exception: ' . $e->getMessage());
            }
        }
    }

    /**
     * Format log entry into a valid, safe Telegram HTML string.
     *
     * @param LogRecord $record
     * @return string
     */
    protected function formatTelegramMessage(LogRecord $record): string
    {
        $appName = GetSystemSettings::get(SystemSettingKey::WEB_NAME);
        $environment = config('app.env', 'local');
        $appName = $appName ?: config('app.name', 'Laravel Base');

        // Escape raw text message to prevent Telegram HTML entity parser failures
        $safeMessage = htmlspecialchars(
            string: substr($record->message, 0, 1000),
            flags: ENT_QUOTES | ENT_SUBSTITUTE,
            encoding: 'UTF-8'
        );

        $message = "🚨 <b>[{$record->level->name}]</b> - <b>{$appName}</b> ({$environment})\n";
        $message .= "⏱ <b>Time:</b> " . $record->datetime->format('Y-m-d H:i:s T') . "\n\n";
        $message .= "📝 <b>Message:</b>\n<code>{$safeMessage}</code>\n\n";

        // Append Exception details if available in log context
        if (isset($record->context['exception']) && $record->context['exception'] instanceof Throwable) {
            $exception = $record->context['exception'];

            $safeFile = htmlspecialchars($exception->getFile(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $safeExMsg = htmlspecialchars(substr($exception->getMessage(), 0, 500), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

            $message .= "💥 <b>Exception:</b> " . get_class($exception) . "\n";
            $message .= "💬 <b>Detail:</b> {$safeExMsg}\n";
            $message .= "📂 <b>File:</b> {$safeFile}:{$exception->getLine()}\n\n";
        }

        // Context environment details
        if (app()->runningInConsole()) {
            $message .= "🖥 <b>Context:</b> CLI Command\n";
        } else {
            $message .= "🌐 <b>URL:</b> " . htmlspecialchars(request()->fullUrl(), ENT_QUOTES, 'UTF-8') . "\n";
            $message .= "👤 <b>User ID:</b> " . (auth()->id() ?? 'Guest') . "\n";
            $message .= "🌐 <b>IP:</b> " . request()->ip() . "\n";
        }

        return $message;
    }
}
