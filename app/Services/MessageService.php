<?php
namespace App\Services;

use App\Models\Message;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class MessageService
{
    public function storeMessage($messageText, $recipient, $readOnce=false)
    {
        $identifier = Str::uuid()->toString();
        $encryptedMessage = Crypt::encryptString($messageText);
        $decryptionKey = base64_encode($identifier);


        // Store the message
        $message = Message::create([
            'identifier' => $identifier,
            'encrypted_message' => $encryptedMessage,
            'decryption_key' => $decryptionKey,
            'recipient' => $recipient,
            'expires_at' => now()->addMinutes(10),
            'read_once' => $readOnce ? true : false,
        ]);

        return [
            'identifier' => $message->identifier,
            'decryption_key' => $decryptionKey,
        ];
    }

    public function getMessage($identifier, $decryptionKey)
    {
        $message = Message::where('identifier', $identifier)
                        ->where('decryption_key', $decryptionKey)
                        ->withTrashed()
                        ->first();


        // Check if message exists
        if (!$message) {
            return [
                'success' => false, 
                'message' => 'Message not found. Check identifier or decryption_key.',
            ];
        }

        // Check if message has expired
        if ($message->expires_at && now()->greaterThan($message->expires_at)) {
            $message->delete();
            return [
                'success' => false, 
                'message' => 'Message has expired',
            ];
        }


        // Check if message has been read once
        if ($message->read_once && $message->read_at) {
            $message->delete();
            return [
                'success' => false, 
                'message' => 'You can only view this message once',
            ];
        }

        // Decrypt the message
        $decryptedMessage = Crypt::decryptString($message->encrypted_message);
        
        // Mark the message as read
        if (!$message->read_at)
        {
            $message->read_at = now();
            $message->save();

            return [
                'success' => true, 
                'message' => $decryptedMessage,
            ];

        }

        return [
            'success' => true, 
            'message' => $decryptedMessage,
        ];
    }

}