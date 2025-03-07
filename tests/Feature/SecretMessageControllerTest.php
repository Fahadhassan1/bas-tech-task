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
     
        $response = $this->postJson('/api/message/store', [
            'message' => 'This is a secret message',
            'recipient' => 'John Doe',
            'read_once' => true,
        ]);

        $response->assertJsonStructure([
            'identifier',
            'decryption_key',
        ]);

        $identifier = $response['identifier'];
        $decryptionKey = $response['decryption_key'];

        $response = $this->getJson('/api/message/show', [
            'identifier' => $identifier,
            'decryption_key' => $decryptionKey,
        ]);

        $response->assertJsonStructure([
            'message',
        ]);
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
