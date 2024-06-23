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
Route::name('v1.')->prefix('v1')->group(function () {
    Route::post('/login', [UserController::class, "submit"]);
    Route::post('/login/authorise', [UserController::class, "authorise"]);
    

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::get('/comments', [ShowCommentController::class, 'sortByDate']);
        Route::get('/comments/offset/{offset}', [ShowCommentController::class, 'offset']);
        Route::get('/comments/reverse', [ShowCommentController::class, 'sortByDateReverse']);
        Route::get('/comments/reverse/offset/{ofsset}', [ShowCommentController::class, 'offsetReverse']);

        Route::get('/comments/email', [ShowCommentController::class, 'sortByEmail']);
        Route::get('/comments/email/offset/{offset}', [ShowCommentController::class, 'offsetSortByEmail']);
        Route::get('/comments/email/reverse', [ShowCommentController::class, 'sortByEmailReverse']);
        Route::get('/comments/email/reverse/offset/{ofsset}', [ShowCommentController::class, 'sortByEmailReverseOffset']);

        Route::get('/comments/name', [ShowCommentController::class, 'sortByName']);
        Route::get('/comments/name/offset/{offset}', [ShowCommentController::class, 'offsetsortByName']);
        Route::get('/comments/name/reverse', [ShowCommentController::class, 'sortByNameReverse']);
        Route::get('/comments/name/reverse/offset/{ofsset}', [ShowCommentController::class, 'sortByNameReverseOffset']);

        Route::post('/comment/add', [StoreCommentController::class, 'store'])->middleware("XSS");

        Route::get('/comments/photo/{image}', [ImageController::class, 'getImage']);
        Route::get('/comments/txt/{txt}', [StoreCommentController::class, 'store']);
    });


});



