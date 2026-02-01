<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {url?}';
    protected $description = 'Set or delete the Telegram bot webhook';

    public function handle()
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) {
            $this->error("TELEGRAM_BOT_TOKEN not found in .env");
            return;
        }

        $this->info("Using token: " . substr($token, 0, 10) . "...");

        $bot = new Nutgram($token, [
            'client' => ['verify' => false]
        ]);
        
        $url = $this->argument('url');

        if ($url) {
            $webhookUrl = rtrim($url, '/') . '/telegram/webhook';
            $this->info("Setting webhook to: {$webhookUrl}");
            try {
                $bot->setWebhook($webhookUrl);
                $this->info("✅ Webhook set successfully!");
            } catch (\Exception $e) {
                $this->error("❌ Failed to set webhook: " . $e->getMessage());
            }
        } else {
            $this->info("Deleting webhook to enable polling mode...");
            try {
                $bot->deleteWebhook();
                $this->info("✅ Webhook deleted! You can now use 'php artisan telegram:run' for local testing.");
            } catch (\Exception $e) {
                $this->error("❌ Failed to delete webhook: " . $e->getMessage());
            }
        }
    }
}
