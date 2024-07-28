<?php 

namespace App\Services;
use App\Models\Comment;
use App\Services\CommentService;
use App\Models\ReplyComment;
use Illuminate\Database\Eloquent\Builder;

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
        $this->loadAdditionalRepliesToComments($data);
        return $sortedByNameComments;
    }

    public function sortCommentsByNameReversed(string $name, int $offset = 0)
    {
        $sortedByNameComments = $this->getReversedComments($offset)->where('users.name', "=", $name)->get();
        $this->loadAdditionalRepliesToComments($data);
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


    public function loadAdditionalRepliesToComments(&$data): void
    {
        for ($i = 0; $i < count($data); $i++){            
            $data[$i]['reply_comments'] = ReplyComment::where('comment_id', '=', $data[$i]['id'])->get();
            if (isset($data[$i]['photo_file']))
            {       
                $data[$i]['photo_file'] = $this->loadImage($data[$i]['photo_file']);
            }
            if (isset($data['txt_file']))
            {
                $data[$i]['photo_file'] = $this->loadImage($data[$i]['txt_file']);
            }

            for ($j = 0; $j < count($data[$i]['reply_comments']); $j++){
                // echo $data[$i]['reply_comments'][$j]['comment_reply_id'];
                $data[$i]['reply_comments'][$j]['comment'] = Comment::where('id', '=', $data[$i]['reply_comments'][$j]['comment_reply_id'])->with('user:id,name,email')->get();
                $this->loadAdditionalRepliesToComments($data[$i]['reply_comments'][$j]['comment']);
                // echo json_encode(Comment::where('id', '=', $data[$i]['reply_comments'][$j]['comment_reply_id'])->get());
            } 
        }


    }

    public function loadImage(string $image_path): string
    {
        return url("/comments/photo/".$image_path);
    }

    public function loadText(string $text_file_path): string
    {
        return url("/comments/txt/".$text_file_path);
    }

}