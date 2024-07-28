<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;

use App\Models\Comment;
use App\Models\ReplyComment;


class StoreReplyToCommentController extends Controller
{
    public static function storeReply(int $reply_to_comment_id, int $current_comment_id, int $user_id): void
    {
        $comment_reply = Comment::findOrFail($reply_to_comment_id);
        if ($comment_reply){
            ReplyComment::create([
                'user_id' => $user_id,
                'comment_id' => $comment_reply->id,
                'comment_reply_id' => $current_comment_id
            ]);
        }
    }
}
