<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'body',
        'file_path',
        'file_type',
        'original_name',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // L-conversation li tatawade3 fiha l-message
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    // L-user li sft l-message
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}