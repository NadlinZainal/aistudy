<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/students', [App\Http\Controllers\StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create',[App\Http\Controllers\StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [App\Http\Controllers\StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [App\Http\Controllers\StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [App\Http\Controllers\StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [App\Http\Controllers\StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [App\Http\Controllers\StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/halls', [App\Http\Controllers\HallController::class, 'index'])->name('halls.index');
    Route::get('/halls/create',[App\Http\Controllers\HallController::class, 'create'])->name('halls.create');
    Route::post('/halls', [App\Http\Controllers\HallController::class, 'store'])->name('halls.store');
    Route::get('/halls/{hall}', [App\Http\Controllers\HallController::class, 'show'])->name('halls.show');
    Route::get('/halls/{hall}/edit', [App\Http\Controllers\HallController::class, 'edit'])->name('halls.edit');
    Route::put('/halls/{hall}', [App\Http\Controllers\HallController::class, 'update'])->name('halls.update');
    Route::delete('/halls/{hall}', [App\Http\Controllers\HallController::class, 'destroy'])->name('halls.destroy');

    Route::get('/subjects', [App\Http\Controllers\SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/create',[App\Http\Controllers\SubjectController::class, 'create'])->name('subjects.create');
    Route::post('/subjects', [App\Http\Controllers\SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{subject}', [App\Http\Controllers\SubjectController::class, 'show'])->name('subjects.show');
    Route::get('/subjects/{subject}/edit', [App\Http\Controllers\SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/subjects/{subject}', [App\Http\Controllers\SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [App\Http\Controllers\SubjectController::class, 'destroy'])->name('subjects.destroy');

    Route::get('/groups', [App\Http\Controllers\GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create',[App\Http\Controllers\GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [App\Http\Controllers\GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [App\Http\Controllers\GroupController::class, 'show'])->name('groups.show');
    Route::get('/groups/{group}/edit', [App\Http\Controllers\GroupController::class, 'edit'])->name('groups.edit');
    Route::put('/groups/{group}', [App\Http\Controllers\GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{group}', [App\Http\Controllers\GroupController::class, 'destroy'])->name('groups.destroy');

    Route::get('/days', [App\Http\Controllers\DayController::class, 'index'])->name('days.index');
    Route::get('/days/create',[App\Http\Controllers\DayController::class, 'create'])->name('days.create');
    Route::post('/days', [App\Http\Controllers\DayController::class, 'store'])->name('days.store');
    Route::get('/days/{day}', [App\Http\Controllers\DayController::class, 'show'])->name('days.show');
    Route::get('/days/{day}/edit', [App\Http\Controllers\DayController::class, 'edit'])->name('days.edit');
    Route::put('/days/{day}', [App\Http\Controllers\DayController::class, 'update'])->name('days.update');
    Route::delete('/days/{day}', [App\Http\Controllers\DayController::class, 'destroy'])->name('days.destroy');

    Route::get('/timetable', [App\Http\Controllers\TimetableController::class, 'index'])->name('timetable.index');
    Route::get('/timetable/create',[App\Http\Controllers\TimetableController::class, 'create'])->name('timetable.create');
    Route::post('/timetable', [App\Http\Controllers\TimetableController::class, 'store'])->name('timetable.store');
    Route::get('/timetables/{timetable}', [App\Http\Controllers\TimetableController::class, 'show'])->name('timetable.show');
    Route::get('/timetables/{timetable}/edit', [App\Http\Controllers\TimetableController::class, 'edit'])->name('timetable.edit');
    Route::put('/timetables/{timetable}', [App\Http\Controllers\TimetableController::class, 'update'])->name('timetable.update');
    Route::delete('/timetables/{timetable}', [App\Http\Controllers\TimetableController::class, 'destroy'])->name('timetable.destroy');

    Route::get('/flashcard', [App\Http\Controllers\FlashcardController::class, 'index'])->name('flashcard.index');
    Route::get('/flashcard/create',[App\Http\Controllers\FlashcardController::class, 'create'])->name('flashcard.create');
    Route::post('/flashcard', [App\Http\Controllers\FlashcardController::class, 'store'])->name('flashcard.store');
    Route::get('/flashcard/{flashcard}', [App\Http\Controllers\FlashcardController::class, 'show'])->name('flashcard.show');
    Route::get('/flashcard/{flashcard}/edit', [App\Http\Controllers\FlashcardController::class, 'edit'])->name('flashcard.edit');
    Route::put('/flashcard/{flashcard}', [App\Http\Controllers\FlashcardController::class, 'update'])->name('flashcard.update');
    Route::get('/favorites', [App\Http\Controllers\FlashcardController::class, 'favorites'])->name('flashcard.favorites');
    Route::post('/flashcard/{flashcard}/toggle-favorite', [App\Http\Controllers\FlashcardController::class, 'toggleFavorite'])->name('flashcard.toggle-favorite');
    Route::get('/flashcard/{flashcard}/summarize', [App\Http\Controllers\FlashcardController::class, 'summarize'])->name('flashcard.summarize');
    Route::get('/flashcard/{flashcard}/export', [App\Http\Controllers\FlashcardController::class, 'export'])->name('flashcard.export');
    Route::get('/flashcard/{flashcard}/export/pdf', [App\Http\Controllers\FlashcardController::class, 'exportPdf'])->name('flashcard.export.pdf');

    Route::post('/flashcard/chat', [App\Http\Controllers\FlashcardController::class, 'chat'])->name('flashcard.chat');
    Route::post('/flashcard/progress', [App\Http\Controllers\FlashcardController::class, 'updateProgress'])->name('flashcard.progress');
    Route::get('/flashcard/{flashcard}/study', [App\Http\Controllers\FlashcardController::class, 'study'])->name('flashcard.study');
    Route::get('/flashcard/{flashcard}/quiz', [App\Http\Controllers\FlashcardController::class, 'quiz'])->name('flashcard.quiz');
    Route::get('/quiz-history', [App\Http\Controllers\FlashcardController::class, 'quizHistory'])->name('flashcard.quiz-history');
    Route::post('/quiz-result', [App\Http\Controllers\FlashcardController::class, 'saveQuizResult'])->name('flashcard.quiz-result');
    Route::post('/telegram/link', [App\Http\Controllers\TelegramController::class, 'generateLinkToken'])->name('telegram.link');
    Route::delete('/flashcard/{flashcard}', [App\Http\Controllers\FlashcardController::class, 'destroy'])->name('flashcard.destroy');
    Route::patch('/flashcard/{flashcard}/card/{index}', [App\Http\Controllers\FlashcardController::class, 'updateCard'])->name('flashcard.card.update');
    Route::delete('/flashcard/{flashcard}/card/{index}', [App\Http\Controllers\FlashcardController::class, 'destroyCard'])->name('flashcard.card.destroy');
    Route::post('/flashcard/{flashcard}/clone', [App\Http\Controllers\FlashcardController::class, 'clone'])->name('flashcard.clone');

    // Friendships
    Route::get('/friends', [App\Http\Controllers\FriendshipController::class, 'index'])->name('friends.index');
    Route::get('/friends/search', [App\Http\Controllers\FriendshipController::class, 'search'])->name('friends.search');
    Route::post('/friends', [App\Http\Controllers\FriendshipController::class, 'store'])->name('friends.store');
    Route::put('/friends/{id}', [App\Http\Controllers\FriendshipController::class, 'update'])->name('friends.update');
    Route::delete('/friends/{id}', [App\Http\Controllers\FriendshipController::class, 'destroy'])->name('friends.destroy');

    // Direct Messages
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
});

// Telegram Webhook (No CSRF)
Route::post('/telegram/webhook', [App\Http\Controllers\TelegramController::class, 'handle']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');


// Temporary Migration Route for Setup
Route::get('/migrate-me', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate --force');
        return "Migration successful! <a href='/'>Go to Dashboard</a>";
    } catch (\Exception $e) {
        return "Migration failed: " . $e->getMessage();
    }
});
