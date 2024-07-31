<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Comment\StoreReplyToCommentController;
use App\Http\Controllers\User\UserController;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCommentRequest;

use App\Models\Comment;
use App\Models\SavedComment;

use App\Services\FileService;

class StoreCommentController extends Controller
{
    public function storeCommentInDB(StoreCommentRequest $request): object
    {   
        $request->validated();
        $photo_file = app(FileService::class)->storePhotoFileFromRequest($request);
        $txt_file = app(FileService::class)->storeTXTFileFromRequest($request);
        
        if ($request->has("name"))
        {
            UserController::changeUserName($request->user(), $request->name);
        }
        $comment = Comment::create([
            'comment_text'=>$request->comment_text,
            'home_page'=>$request->home_page,
            'txt_file'=> $txt_file,
            'user_id'=>$request->user()->id,
            'photo_file'=>$photo_file
        ]);

        if ($request->has('reply_id')){
            StoreReplyToCommentController::storeReply($request->reply_id, $comment->id, $request->user()->id);
        }
        return $comment;
    }

    public function addCommentToSaved(Comment $comment, Request $request): SavedComment
    {   
        $saved = SavedComment::create([
            'user_id' => $request->user()->id,
            'comment_id' => $comment->id
        ]);

        return $saved;
    }

    public function deleteMessage(Comment $comment, Request $request): void
    {
        if($comment->user_id === $request->user()->id){
            app(FileService::class)->deleteFile($comment->photo_file);
            app(FileService::class)->deleteFile($comment->txt_file);
            $comment->delete();
        }
    }   
}
