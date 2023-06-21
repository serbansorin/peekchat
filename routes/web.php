<?php

use App\Http\Controllers\CommAndLikeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFriendsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Route::middleware(['auth', 'verified'])->group(function () {
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Home page of logged User - All Posts
    Route::get('/', [PostController::class, 'index'])->name('home.index');

    # Profile page of logged User

    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users', [UserController::class, 'update'])->name('users.update');

        ## Profile page of logged User - Friends
    // Profile edit page of logged User
    Route::get('/users/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    // Profile update page of logged User
    Route::put('/users/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    // Profile delete page of logged User
    Route::delete('/users/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');


    # Posts

    // stocheaza postul in baza de date
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    // preia posts din baza de date si face update la ele
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    // sterge postul din baza de date
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');


    # Comments
    // stocheaza comentariile in baza de date
    Route::post('/comments', [CommAndLikeController::class, 'createComment'])->name('comments.store');
    // sterge comentariile din baza de date
    Route::delete('/comments/{id}', [CommAndLikeController::class, 'deleteComment'])->name('comments.destroy');

    # Likes
    // stocheaza like in baza de date
    Route::post('/likes', [CommAndLikeController::class, 'createLike'])->name('likes.store');
    // sterge like din baza de date dupa /{id}
    Route::delete('/likes/{id}', [CommAndLikeController::class, 'deleteLike'])->name('likes.destroy');

    # Chat

    ## Messages

    // render chat page
    Route::get('/messages', [MessagesController::class, 'index'])->name('messages.index');

    // get all messages for user
    Route::get('//messages/select', [MessagesController::class, 'getMessages'])->name('messages.get');

    // send message
    Route::post('/send-message', [MessagesController::class, 'sendMessage'])->name('messages.send');

    ## Friends
    // follow request
    Route::post('/friends/follow/{id}', [UserFriendsController::class, 'follow'])->name('users.friends.follow');
    // unfollow user
    Route::post('/friends/unfollow/{id}', [UserFriendsController::class, 'unfollow'])->name('users.friends.unfollow');

    // Friend request
    Route::post('/friends/friend-request/{id}', [UserFriendsController::class, 'createFriendRequest'])->name('users.friends.request');

    // accept friend request
    Route::post('/friends/accept/{id}', [UserFriendsController::class, 'acceptFriendRequest'])->name('users.friends.accept');

    // deny friend request
    Route::post('/friends/deny/{id}', [UserFriendsController::class, 'denyFriendRequest'])->name('users.friends.deny');

    // block user
    Route::post('/friends/block/{id}', [UserFriendsController::class, 'blockFriend'])->name('users.friends.block');

    // unfriend
    Route::post('/friends/unfriend/{id}', [UserFriendsController::class, 'unfriend'])->name('users.friends.unfriend');

    // cancel friend request
    Route::post('/friends/cancel/{id}', [UserFriendsController::class, 'cancelFriendRequest'])->name('users.friends.cancel');

    // get all friends
    Route::get('/friends', [UserFriendsController::class, 'index'])->name('users.friends.index');

});

require __DIR__ . '/auth.php';
