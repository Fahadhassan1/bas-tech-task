<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'identifier' => $this->faker->uuid(),
            'decryption_key' => $this->faker->word(),
            'encrypted_message' => Crypt::encryptString($this->faker->sentence()),
            'expires_at' => now()->addMinutes(10), 
            'read_once' => false,
            'recipient' => $this->faker->word(),
        ];
    }
}
