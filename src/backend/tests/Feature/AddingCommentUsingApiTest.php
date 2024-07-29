<?php

namespace Tests\Feature;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
        $this->authorizationToken = 'Bearer 2|G8WsO1NjXqW8BL0UxakN8xlO4gBcru3zBj4zuFmB0dd8c021';
        $this->headers = $this->withHeaders([
            'Authorization' => $this->authorizationToken,
        ]);
    }

    public function testAddingCommentInApi(): void
    {
        // echo exec('ps -up '.getmypid());
        // echo 'Current sys_temp_dir: ' . sys_get_temp_dir();
        // $tempDir = sys_get_temp_dir();
        // // $testFile = $tempDir . '/test_file.txt';
        // echo $tempDir;
        // file_put_contents($testFile, 'Test content');
        // if (file_exists($testFile)) {
        //     echo 'File created successfully.';
        //     unlink($testFile); // Clean up
        // } else {
        //     echo 'Failed to create file.';
        // }
        // Storage::fake('avatars');
 
        // $file = UploadedFile::fake()->image('avatar.jpg');
        

        // $response = $this->post('/api/v1/comment/add', [
        //     "comment_text" => "<a href='new.wordcard.tech'> dasdasdasaaa dasdasdas </a> hfsdkfl <i> hjhh </i>",
        //     "photo_file" => $file
        // ]);

        // $response->assertStatus(201)->assertCreated();
        // Storage::disk('public/comments/photos')->assertExists($file->hashName());

       // Storage::disk('avatars')->put('test.txt', 'Test content');
        // Storage::disk('avatars')->assertExists('test.txt');
    }
}
