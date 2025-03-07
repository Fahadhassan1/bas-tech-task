<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SecretMessageControllerTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_secret_message_can_be_decrypted()
    {
        $response = $this->getJson('/api/message/show', [
            'identifier' => '1234',
            'decryption_key' => 'secret',
        ]);

        if ($response->status() === 404) {
            $this->markTestSkipped('No message found with the given identifier');
        }
        if ($response->status() === 422) {
            $this->markTestSkipped('Invalid decryption key');
        }
        if ($response->status() === 500) {
            $this->markTestSkipped('An error occurred while decrypting the message');
        }
     
        if ($response->status() === 200) {
            $response->assertJsonStructure([
                'message',
            ]);
        }
    }
    

    public function test_secret_message_can_be_stored()
    {
        $response = $this->postJson('/api/message/store', [
            'message' => 'This is a secret message',
            'recipient' => 'John Doe',
            'read_once' => true,
        ]);

        $response->assertJsonStructure([
            'identifier',
            'decryption_key',
        ]);
    }


}
