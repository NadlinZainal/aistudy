<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Flashcard;
use App\Services\FlashcardGeneratorService;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TelegramController extends Controller
{
    public function generateLinkToken(Request $request)
    {
        $user = $request->user();
        $user->telegram_link_token = Str::random(32);
        $user->save();

        return response()->json([
            'success' => true,
            'token' => $user->telegram_link_token,
            'bot_username' => config('services.telegram.username') 
        ]);
    }

    public function handle(Request $request)
    {
        Log::debug('Telegram Webhook Payload:', $request->all());
        $token = config('services.telegram.token');
        if (!$token) {
            Log::error("Telegram Bot Token is missing in config");
            return response()->json(['error' => 'Bot token not set'], 500);
        }

        $bot = new Nutgram($token, [
            'client' => [
                'verify' => false,
            ],
        ]);

        // Handle /start {token}
        $bot->onCommand('start {token}', function (Nutgram $bot, $token) {
            $user = User::where('telegram_link_token', $token)->first();

            if ($user) {
                // Check if already linked
                if ($user->telegram_chat_id) {
                    $bot->sendMessage("This account is already linked to another Telegram user.");
                    return;
                }

                $user->telegram_chat_id = $bot->chatId();
                $user->telegram_link_token = null; // Consume token
                $user->save();

                $bot->sendMessage("✅ Account linked successfully! You can now send me PDFs, images, or links to create flashcards, or ask me anything using /ask.");
            } else {
                $bot->sendMessage("❌ Invalid or expired token. Please generate a new one from your Profile page.");
            }
        });

        $bot->onCommand('start', function (Nutgram $bot) {
            $bot->sendMessage("Welcome to AIStudy Bot! 🚀\n\nPlease link your account by sending `/start YOUR_TOKEN`.\nYou can find your token on the Profile page of the website.");
        });

        // Feature 3: AI Tutor on the Go
        $bot->onCommand('ask {question}', function (Nutgram $bot, $question) {
            $user = User::where('telegram_chat_id', $bot->chatId())->first();
            if (!$user) {
                $bot->sendMessage("Please link your account first using `/start YOUR_TOKEN`.");
                return;
            }

            $bot->sendChatAction('typing');
            
            $generator = new FlashcardGeneratorService();
            $reply = $generator->chatWithAi($question, "User is chatting via Telegram.");
            
            $bot->sendMessage($reply);
        });

        // Feature 1: Instant Deck Creation (Files - Documents)
        $bot->onMessageType('document', function (Nutgram $bot) {
            $this->handleMedia($bot);
        });

        // Feature 1: Instant Deck Creation (Files - Photos)
        $bot->onMessageType('photo', function (Nutgram $bot) {
            $this->handleMedia($bot);
        });

        // Feature 1: Instant Deck Creation (Links)
        $bot->onText('http{url}', function (Nutgram $bot, $url) {
            $user = User::where('telegram_chat_id', $bot->chatId())->first();
            if (!$user) return; // Don't respond to random links if not linked

            $fullUrl = "http" . $url;
            $bot->sendMessage("🔗 Link detected. Generating flashcards from this URL...");
            
            try {
                $generator = new FlashcardGeneratorService();
                $text = $generator->extractTextFromUrl($fullUrl);
                
                if (!$text) {
                    $bot->sendMessage("❌ Failed to extract content from that link.");
                    return;
                }

                $fileName = 'url_' . time() . '.txt';
                $path = 'flashcard_documents/' . $fileName;
                Storage::disk('public')->put($path, $text);

                $flashcard = Flashcard::create([
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

                $bot->sendMessage("✅ Deck created from link! " . count($cards) . " cards generated.\nPractice here: " . route('flashcard.study', $flashcard->id));

            } catch (\Exception $e) {
                $bot->sendMessage("❌ Error processing the link.");
            }
        });

        // Handle Callback Query for "Reveal Answer"
        $bot->onCallbackQueryData('reveal_{deck_id}_{answer}', function (Nutgram $bot, $deck_id, $answer) {
            $decodedAnswer = base64_decode($answer);
            $bot->editMessageText("❓ *Question:*\n" . $bot->callbackQuery()->message->text . "\n\n✅ *Answer:*\n" . $decodedAnswer, [
                'parse_mode' => 'Markdown'
            ]);
            $bot->answerCallbackQuery();
        });

        $bot->run();
    }

    protected function handleMedia(Nutgram $bot)
    {
        $user = User::where('telegram_chat_id', $bot->chatId())->first();
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
        $bot->sendChatAction('upload_document');

        try {
            $file = $bot->getFile($fileId);
            $url = "https://api.telegram.org/file/bot" . config('services.telegram.token') . "/" . $file->file_path;
            
            $contents = file_get_contents($url);
            $path = 'flashcard_documents/' . Str::random(40) . '_' . $fileName;
            Storage::disk('public')->put($path, $contents);

            $flashcard = Flashcard::create([
                'user_id' => $user->id,
                'title' => pathinfo($fileName, PATHINFO_FILENAME),
                'description' => 'Created via Telegram Bot',
                'document_path' => $path,
                'source_type' => pathinfo($fileName, PATHINFO_EXTENSION) ?: 'jpg',
                'status' => 'processing',
            ]);

            $generator = new FlashcardGeneratorService();
            $cards = $generator->generateFromDocument($flashcard);
            
            $flashcard->cards = $cards;
            $flashcard->status = $cards ? 'completed' : 'failed';
            $flashcard->save();

            if ($flashcard->status === 'completed') {
                $bot->sendMessage("✅ Deck '{$flashcard->title}' created! " . count($cards) . " cards generated.\nPractice here: " . route('flashcard.study', $flashcard->id));
            } else {
                $bot->sendMessage("❌ Failed to generate cards from this document. Please try a different file.");
            }
        } catch (\Exception $e) {
            Log::error("Telegram File Error: " . $e->getMessage());
            $bot->sendMessage("❌ An error occurred while processing your file.");
        }
    }
}
