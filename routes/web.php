<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController; // BookControllerに変更
use App\Http\Controllers\InviteController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::middleware(['auth'])->group(function () {

//本：ダッシュボード表示
    Route::get('/', [BookController::class, 'index'])->name('book_index');
    Route::get('/dashboard', [BookController::class, 'index'])->name('dashboard');

//本：追加
    Route::post('/books', [BookController::class, 'store'])->name('book_store');

//本：削除
    Route::delete('/book/{book}', [BookController::class, 'destroy'])->name('book_destroy');
    
//本：更新画面
    Route::get('/booksedit/{book}', [BookController::class, 'edit'])->name('book_edit');  //Validationエラーありの場合
    Route::put('/books/{book}', [BookController::class, 'update'])->name('book_update');  //PUT メソッドを使用 
    // Route::post('/books/update', [BookController::class, 'update'])->name('book_update'); //POST メソッドを使用
    
});

//ログイン機能
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//家族招待機能
Route::get('invite', [InviteController::class, 'showLinkRequestForm'])->name('invite')->middleware('auth');
Route::post('invite', [InviteController::class, 'sendInviteFamilyEmail'])->name('invite.email')->middleware('auth');
Route::get('register/invited/{token}', [RegisterController::class, 'showInvitedUserRegistrationForm'])->name('register.invited.{token}');
Route::post('register/invited', [RegisterController::class, 'registerInvitedUser'])->name('register.invited');

Route::get('register/invited/{token}', [RegisteredUserController::class, 'showInvitedUserRegistrationForm'])
    ->name('register.invited.{token}')
    ->middleware('guest');
Route::post('register/invited', [RegisteredUserController::class, 'registerInvitedUser'])
    ->name('register.invited')
    ->middleware('guest');

require __DIR__.'/auth.php';