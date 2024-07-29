<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GetCommentsDataWithReverseDateTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    protected $authorizationToken;
    protected $headers;
    protected function setUp(): void
    {
        parent::setUp();
        $this->authorizationToken = 'Bearer 2|G8WsO1NjXqW8BL0UxakN8xlO4gBcru3zBj4zuFmB0dd8c021';
        $this->headers = $this->withHeaders([
            'Authorization' => $this->authorizationToken,
        ]);
    }

    public function testGetCommentsWithAuthorizationTokenFromApi(): void
    {
        $response = $this->headers->getJson('/api/v1/comments');

        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) =>
            $json->has('0.id')
            ->whereType('0.id', 'integer')
            ->whereType('0.reply_comments', 'array|null')
        );
    }

    public function testGetCommentsReversedFromApi(): void
    {
        $response = $this->headers->getJson('/api/v1/comments/reverse');

        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) =>
            $json->has('0.id')
            ->whereType('0.id', 'integer')
            ->whereType('0.reply_comments', 'array|null')
        );
    }

    public function testGetCertianCommentExampleAssertionFromApi(): void
    {
        $response = $this->headers->getJson('/api/v1/comments');

        //->assertJsonPath('0.id', 21) - that is for the certain element
        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) =>
            $json->has('0.id')
            ->whereType('0.id', 'integer')
             ->where('0.photo_file', null)
             ->where('0.txt_file', null)
        );
    }





}
