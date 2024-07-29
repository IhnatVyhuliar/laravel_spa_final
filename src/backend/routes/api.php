<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Comment\ShowCommentController;
use App\Http\Controllers\Comment\StoreCommentController;
use App\Http\Controllers\ImageController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/login', [UserController::class, "submit"])->name("login");
Route::post('/login/authorise', [UserController::class, "authorise"]);

Route::name('v1.')->prefix('v1')->group(function () {
    Route::post('/login', [UserController::class, "submit"]);
    Route::post('/login/authorise', [UserController::class, "authorise"]);
    

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::get('/comments', [ShowCommentController::class, 'sortCommentsByDate']);
        Route::get('/comments/offset/{offset}', [ShowCommentController::class, 'sortCommentsOffset']);
        Route::get('/comments/reverse', [ShowCommentController::class, 'sortCommentsByDateReversed']);
        Route::get('/comments/reverse/offset/{ofsset}', [ShowCommentController::class, 'sortCommentsReversedOffset']);

        Route::get('/comments/email', [ShowCommentController::class, 'sortCommentsByEmail']);
        Route::get('/comments/email/offset/{offset}', [ShowCommentController::class, 'sortCommentsByEmailOffset']);
        Route::get('/comments/email/reverse', [ShowCommentController::class, 'sortCommentsByEmailReversed']);
        Route::post('/comments/email/reverse/offset/{ofsset}', [ShowCommentController::class, 'sortCommentsByEmailReversedOffset']);

        Route::get('/comments/name', [ShowCommentController::class, 'sortCommentsByName']);
        Route::get('/comments/name/offset/{offset}', [ShowCommentController::class, 'sortCommentsByNameOffset']);
        Route::get('/comments/name/reverse', [ShowCommentController::class, 'sortCommentsByNameReversed']);
        Route::post('/comments/name/reverse/offset/{ofsset}', [ShowCommentController::class, 'sortCommentsByNameReversedOffset']);

        Route::post('/comment/add', [StoreCommentController::class, 'store'])->middleware("XSS");

        Route::get('/comments/photo/{image}', [ImageController::class, 'getImage']);
        Route::get('/comments/txt/{txt}', [StoreCommentController::class, 'store']);
    });


});



