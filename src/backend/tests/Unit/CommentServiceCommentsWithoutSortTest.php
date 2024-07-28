<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Services\CommentService;
use App\Models\Comment;
use Mockery;

class CommentServiceCommentsWithoutSortTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    protected  $commentService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->commentService = new CommentService(10);     
    }

    public function test_example(): void
    {
        
        $this->assertTrue(true);
    }
}
