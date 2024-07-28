<?php 

    namespace App\Services;
    use App\Models\Comment;

    use Illuminate\Database\Eloquent\Builder;

    class CommentService
    {
        public function __construct(
            protected int $limit
        ){}

        public function getUnsortedCommentsBuilder(): Builder
        {
            return $this->getOriginUnsortedComments("DESC");
        }
        
        public function getCommentsReversedBuilder(): Builder
        {
            return $this->getOriginUnsortedComments("ASC");
        }

        public function getCommentsReversedWithOffsetBuilder(int $offset): Builder
        {
            return $this->getOriginUnsortedComments("ASC")->skip($offset);
        }
        
        public function getCommentsWithOffsetBuilder(int $offset): Builder
        {
            return $this->getOriginUnsortedComments("DESC")->skip($offset);
        }

        public function getOriginUnsortedComments(string $sortparam): Builder
        {
            $unsortedCommentsBuilder = Comment::leftJoin('reply_comments', 'reply_comments.comment_reply_id', '=', 'comments.id')
            ->leftJoin('users', 'users.id', '=', 'comments.user_id')
            ->whereNull('comment_reply_id')
            ->select('comments.*')
            ->limit($this->limit)
            ->orderBy("comments.created_at", $sortparam)
            // ->with('replyComments.comment:id,home_page,comment_text,txt_file,photo_file,user_id,created_at')
            // ->with('replyComments.user:*')
            ->with('user:id,name,email');

            return $unsortedCommentsBuilder;
        }
    }