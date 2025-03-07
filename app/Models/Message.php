<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $fillable = ['identifier', 'encrypted_message', 'decryption_key', 'recipient', 'expires_at', 'read_once', 'read_at', 'deleted_at'];
    protected $casts = [
        'expires_at' => 'datetime',
        'read_once' => 'boolean',
        'read_at' => 'datetime',
    ];
}
