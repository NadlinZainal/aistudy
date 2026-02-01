<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TelegramController;
use SergiX44\Nutgram\Nutgram;

class RunTelegramBot extends Command
{
    protected $signature = 'telegram:run';
    protected $description = 'Run the Telegram bot in polling mode (for local testing)';

    public function handle()
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) {
            $this->error("TELEGRAM_BOT_TOKEN not found in .env");
            return;
        }

        $this->info("Starting Telegram Bot (Polling mode)...");
        
        $bot = new Nutgram($token);
        
        // We reuse the logic from the controller
        $controller = new TelegramController();
        
        // We need to simulate the handle call but in polling mode.
        // Actually, the handle method in the controller now initializes Nutgram.
        // It's better to refactor the logic into a service or just define it here.
        
        // Since the controller's handle method calls $bot->run(), 
        // and Nutgram detects it's not a webhook if no data is in php://input, 
        // it might fall back to polling if configured or just run once?
        // Actually, Nutgram needs to be told to use polling.
        
        $bot = new Nutgram($token, [
            'timeout' => 30,
            'client' => [
                'verify' => false,
            ],
        ]);
        
        // Map the commands (we could extract this to a common method)
        $this->setupBot($bot);
        
        $this->info("Bot is running. Press Ctrl+C to stop.");
        $bot->run();
    }

    protected function setupBot(Nutgram $bot)
    {
        $bot->middleware(function (Nutgram $bot, $next) {
            Log::info("Telegram Update Received: " . json_encode($bot->update()));
            $next($bot);
        });

        $bot->onCommand('start {token}', function (Nutgram $bot, $token) {
            $user = \App\Models\User::where('telegram_link_token', $token)->first();

            if ($user) {
                if ($user->telegram_chat_id) {
                    $bot->sendMessage("This account is already linked to another Telegram user.");
                    return;
                }

                $user->telegram_chat_id = $bot->chatId();
                $user->telegram_link_token = null;
                $user->save();

                $bot->sendMessage("✅ Account linked successfully! You can now send me PDFs, images, or links to create flashcards, or ask me anything using /ask.");
            } else {
                $bot->sendMessage("❌ Invalid or expired token. Please generate a new one from your Profile page.");
            }
        });

        $bot->onCommand('start', function (Nutgram $bot) {
            $bot->sendMessage("Welcome to AIStudy Bot! 🚀\n\nPlease link your account by sending `/start YOUR_TOKEN`.\nYou can find your token on the Profile page of the website.");
        });

        $bot->onCommand('ask {question}', function (Nutgram $bot, $question) {
            $user = \App\Models\User::where('telegram_chat_id', $bot->chatId())->first();
            if (!$user) {
                $bot->sendMessage("Please link your account first using `/start YOUR_TOKEN`.");
                return;
            }

            $bot->sendChatAction('typing');
            $generator = new \App\Services\FlashcardGeneratorService();
            $reply = $generator->chatWithAi($question, "User is chatting via Telegram.");
            $bot->sendMessage($reply);
        });

        $bot->onMessageType('document', function (Nutgram $bot) {
            $this->handleMedia($bot);
        });

        $bot->onMessageType('photo', function (Nutgram $bot) {
            $this->handleMedia($bot);
        });

        $bot->onText('http{url}', function (Nutgram $bot, $url) {
            $user = \App\Models\User::where('telegram_chat_id', $bot->chatId())->first();
            if (!$user) return;

            $fullUrl = "http" . $url;
            $bot->sendMessage("🔗 Link detected. Generating flashcards...");
            
            try {
                $generator = new \App\Services\FlashcardGeneratorService();
                $text = $generator->extractTextFromUrl($fullUrl);
                
                if (!$text) {
                    $bot->sendMessage("❌ Failed to extract content.");
                    return;
                }

                $fileName = 'url_' . time() . '.txt';
                $path = 'flashcard_documents/' . $fileName;
                \Illuminate\Support\Facades\Storage::disk('public')->put($path, $text);

                $flashcard = \App\Models\Flashcard::create([
                    'user_id' => $user->id,
                    'title' => 'Web Import via Bot',
                    'description' => "Imported from: {$fullUrl}",
                    'document_path' => $path,
                    'source_type' => 'url',
                    'status' => 'processing',
                ]);

                $cards = $generator->generateFromDocument($flashcard);
                $flashcard->cards = $cards;
                $flashcard->status = $cards ? 'completed' : 'failed';
                $flashcard->save();

                $bot->sendMessage("✅ Deck created from link!");

            } catch (\Exception $e) {
                $bot->sendMessage("❌ Error processing the link.");
            }
        });

        $bot->onCallbackQueryData('reveal_{deck_id}_{answer}', function (Nutgram $bot, $deck_id, $answer) {
            $decodedAnswer = base64_decode($answer);
            $bot->editMessageText("❓ *Question:*\n" . $bot->callbackQuery()->message->text . "\n\n✅ *Answer:*\n" . $decodedAnswer, [
                'parse_mode' => 'Markdown'
            ]);
            $bot->answerCallbackQuery();
        });
    }

    protected function handleMedia(Nutgram $bot)
    {
        $user = \App\Models\User::where('telegram_chat_id', $bot->chatId())->first();
        if (!$user) {
            $bot->sendMessage("Please link your account first.");
            return;
        }

        if ($bot->message()->photo) {
            $photos = $bot->message()->photo;
            $document = end($photos);
            $fileName = "telegram_photo_" . time() . ".jpg";
        } else {
            $document = $bot->message()->document;
            $fileName = $document->file_name;
        }
        
        $fileId = $document->file_id;
        $bot->sendMessage("📥 Receiving '{$fileName}'... Processing your deck now.");

        try {
            $file = $bot->getFile($fileId);
            $url = "https://api.telegram.org/file/bot" . env('TELEGRAM_BOT_TOKEN') . "/" . $file->file_path;
            $contents = file_get_contents($url);
            $path = 'flashcard_documents/' . \Illuminate\Support\Str::random(40) . '_' . $fileName;
            \Illuminate\Support\Facades\Storage::disk('public')->put($path, $contents);

            $flashcard = \App\Models\Flashcard::create([
                'user_id' => $user->id,
                'title' => pathinfo($fileName, PATHINFO_FILENAME),
                'description' => 'Created via Telegram Bot',
                'document_path' => $path,
                'source_type' => pathinfo($fileName, PATHINFO_EXTENSION) ?: 'jpg',
                'status' => 'processing',
            ]);

            $generator = new \App\Services\FlashcardGeneratorService();
            $cards = $generator->generateFromDocument($flashcard);
            $flashcard->cards = $cards;
            $flashcard->status = $cards ? 'completed' : 'failed';
            $flashcard->save();

            if ($flashcard->status === 'completed') {
                $bot->sendMessage("✅ Deck '{$flashcard->title}' created! " . count($cards) . " cards generated.");
            } else {
                $bot->sendMessage("❌ Failed to generate cards.");
            }
        } catch (\Exception $e) {
            $bot->sendMessage("❌ Error: " . $e->getMessage());
        }
    }
}
