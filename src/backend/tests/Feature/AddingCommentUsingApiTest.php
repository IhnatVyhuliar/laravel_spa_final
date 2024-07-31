<?php

namespace Tests\Feature;


use Tests\TestCase;

class AddingCommentUsingApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected $authorizationToken;
    protected $headers;
    protected function setUp(): void
    {
        parent::setUp();
        $this->authorizationToken = 'Bearer 2|G8WsO1NjXqW8BL0UxakN8xlO4gBcru3zBj4zuFmB0dd8c021'; // local token not available
        $this->headers = $this->withHeaders([
            'Authorization' => $this->authorizationToken,
        ]);
    }

    public function testAddingCommentInApi(): void
    {

        $response = $this->post('/api/v1/comment/add', [
            "comment_text" => "<a href='new.wordcard.tech'> 
            dasdasdasaaa dasdasdas </a> Testing adding  <i> hjhh </i>",
            'name' => "hello111"
        ]);

        $response->assertStatus(201)->assertCreated();

    }      
}
