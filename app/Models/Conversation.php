<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'is_blocked',
        'blocked_by',
    ];

    // L-user lowl f conversation
    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    // L-user tani f conversation
    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    // Ga3 l-messages li f had conversation
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}