<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Comment\StoreReplyToCommentController;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Models\SavedComment;
use App\Services\FileService;

class StoreCommentController extends Controller
{
    public function store(StoreCommentRequest $request): object
    {   
        $photo_file = app(FileService::class)->storePhotoFileFromRequest($request);
        $txt_file = app(FileService::class)->storeTXTFileFromRequest($request);
        
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

    


    public function addToSaved(Comment $comment, Request $request): SavedComment
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
