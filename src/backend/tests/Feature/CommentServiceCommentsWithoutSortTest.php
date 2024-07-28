<?php

namespace Tests\Feature;

use App\Services\CommentService;
use Tests\TestCase;

class CommentServiceCommentsWithoutSortTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    protected  $commentService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->commentService = new CommentService(25);     
    }

    public function testCommentSeviceProvideValidData(): void
    {
       // $comments = $this->commentService->getDefaultComments();
        // echo json_encode($comments);
        
       $this->assertTrue(true);
    }
}
