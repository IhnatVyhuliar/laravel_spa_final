<?php 

    namespace App\Services;
    use App\Models\Comment;
    use App\Models\ReplyComment;
    use Illuminate\Database\Eloquent\Builder;

    class CommentService
    {
        public function __construct(
            protected int $limit
        ){}

        public function getComments(bool $reverse=false, int $offset = 0): Builder
        {
            $sortparam = "DESC";
            if ($reverse){
                // echo "bu";
                $sortparam = "ASC";
            }
            $data = Comment::leftJoin('reply_comments', 'reply_comments.comment_reply_id', '=', 'comments.id')
                    ->leftJoin('users', 'users.id', '=', 'comments.user_id')
                    ->whereNull('comment_reply_id')
                    ->select('comments.*')
                    ->limit($this->limit)
                    ->skip($offset)
                    ->orderBy("comments.created_at", $sortparam)
                    // ->with('replyComments.comment:id,home_page,comment_text,txt_file,photo_file,user_id,created_at')
                    // ->with('replyComments.user:*')
                    ->with('user:id,name,email');
            
            return $data;
        }
    
        public function sortByEmail(string $email, bool $reverse = false, int $offset = 0)
        {
            $data = $this->getComments($reverse, $offset)->where('users.email', "=", $email)->get();
            $this->loadAdditionalData($data);
            return $data;
            //->file('');
        }

        public function sortByName(string $name, bool $reverse = false, int $offset = 0)
        {
            $data = $this->getComments($reverse, $offset)->where('users.name', "=", $name)->get();
            $this->loadAdditionalData($data);
            return $data;
        }

        public function getDefaultComments(bool $reverse = false, int $offset = 0)
        {
            $data = $this->getComments($reverse, $offset)->get();
            $this->loadAdditionalData($data);
            return $data;
            
        }

        public function loadAdditionalData (&$data): void
        {
            for ($i = 0; $i < count($data); $i++){
                // echo json_encode($data[$i]);
                // echo $data[$i]['id'] . "\n";
                
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
                    $this->loadAdditionalData($data[$i]['reply_comments'][$j]['comment']);
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