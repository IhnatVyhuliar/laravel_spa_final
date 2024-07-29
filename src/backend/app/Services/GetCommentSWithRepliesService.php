<?php 

namespace App\Services;
use App\Models\Comment;
use App\Services\CommentService;
use App\Models\ReplyComment;
use Illuminate\Database\Eloquent\Builder;

use App\Services\LoadFileUsingLinkService;

class GetCommentSWithRepliesService extends CommentService
{
    public function __construct(
        protected int $limit
    ){}

    public function getComments( int $offset = 0): Builder
    {
        return parent::getCommentsWithOffsetBuilder($offset);
    }
    
    public function getReversedComments( int $offset = 0): Builder
    {
        return parent::getCommentsReversedWithOffsetBuilder($offset);
    }

    public function sortCommentsByEmail(string $email, int $offset = 0)
    {
        $sortedByEmailComments = $this->getComments($offset)->where('users.email', "=", $email)->get();
        $this->loadAdditionalRepliesToComments($sortedByEmailComments);
        return $sortedByEmailComments;
    }

    public function sortCommentsByEmailReversed(string $email, int $offset = 0)
    {
        $sortedByEmailComments = $this->getReversedComments($offset)->where('users.email', "=", $email)->get();
        $this->loadAdditionalRepliesToComments($sortedByEmailComments);
        return $sortedByEmailComments;
    }

    public function sortCommentsByName(string $name, int $offset = 0)
    {
        $sortedByNameComments = $this->getComments($offset)->where('users.name', "=", $name)->get();
        $this->loadAdditionalRepliesToComments($sortedByNameComments);
        return $sortedByNameComments;
    }

    public function sortCommentsByNameReversed(string $name, int $offset = 0)
    {
        $sortedByNameComments = $this->getReversedComments($offset)->where('users.name', "=", $name)->get();
        $this->loadAdditionalRepliesToComments($sortedByNameComments);
        return $sortedByNameComments;
    }

    public function getDefaultComments(int $offset = 0)
    {
        $unsortedComments = $this->getComments($offset)->get();
        $this->loadAdditionalRepliesToComments($unsortedComments);
        return $unsortedComments;
    }

    public function getDefaultCommentsReversed(int $offset = 0)
    {
        $unsortedComments = $this->getReversedComments($offset)->get();
        $this->loadAdditionalRepliesToComments($unsortedComments);
        return $unsortedComments;
    }


    public function loadAdditionalRepliesToComments(&$orignalComment): void
    {
        for ($i = 0; $i < count($orignalComment); $i++){            
            $orignalComment[$i]['reply_comments'] = ReplyComment::where('comment_id', '=', $orignalComment[$i]['id'])->get();
            if (isset($orignalComment[$i]['photo_file']))
            {       
                $orignalComment[$i]['photo_file'] = LoadFileUsingLinkService::GetImageLink($orignalComment[$i]['photo_file']);
            }
            if (isset($orignalComment['txt_file']))
            {
                $orignalComment[$i]['photo_file'] = LoadFileUsingLinkService::GetTXTLink($orignalComment[$i]['txt_file']);
            }

            for ($j = 0; $j < count($orignalComment[$i]['reply_comments']); $j++){
                $orignalComment[$i]['reply_comments'][$j]['comment'] = Comment::where('id', '=', $orignalComment[$i]['reply_comments'][$j]['comment_reply_id'])->with('user:id,name,email')->get();
                $this->loadAdditionalRepliesToComments($orignalComment[$i]['reply_comments'][$j]['comment']);
            } 
        }
    }

}