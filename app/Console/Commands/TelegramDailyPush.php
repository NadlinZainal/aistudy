<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Flashcard;
use SergiX44\Nutgram\Nutgram;
use Illuminate\Support\Facades\Log;

class TelegramDailyPush extends Command
{
    protected $signature = 'telegram:daily-push';
    protected $description = 'Send daily flashcards to linked Telegram users';

    public function handle(Nutgram $bot)
    {
        $users = User::whereNotNull('telegram_chat_id')->get();

        foreach ($users as $user) {
            try {
                // Get a random flashcard deck for the user
                $deck = Flashcard::where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->inRandomOrder()
                    ->first();

                if ($deck && !empty($deck->cards)) {
                    $randomCard = $deck->cards[array_rand($deck->cards)];
                    
                    $message = "🌟 *Daily Micro-Learning* 🌟\n\n";
                    $message .= "*Deck:* {$deck->title}\n\n";
                    $message .= "❓ *Question:*\n{$randomCard['question']}\n\n";
                    $message .= "💡 *Think about the answer...*";

                    $bot->sendMessage($message, [
                        'chat_id' => $user->telegram_chat_id,
                        'parse_mode' => 'Markdown',
                        'reply_markup' => json_encode([
                            'inline_keyboard' => [[
                                ['text' => '👁️ Show Answer', 'callback_data' => 'reveal_' . $deck->id . '_' . base64_encode($randomCard['answer'])]
                            ]]
                        ])
                    ]);
                }
            } catch (\Exception $e) {
                Log::error("Failed to send daily push to user {$user->id}: " . $e->getMessage());
            }
        }

        $this->info('Daily push notifications sent.');
    }
}
