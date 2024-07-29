<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorizationSanctumMiddleWareWorksCorrectylyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testAuthorizationForGettingUserFromApiWithoutAuthorization(): void
    {
        $response = $this->json('GET', '/api/v1/user');
        $response->assertStatus(401);
    }

    public function testAuthorizationForGettingCommentsFromApiWithoutAuthorization(): void
    {
        $response = $this->json('GET', '/api/v1/comments');
        $response->assertStatus(401);
    }

}
