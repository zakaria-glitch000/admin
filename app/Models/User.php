<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // <-- 1. إضافة HasRoles من Spatie

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles; // <-- 2. تفعيل HasRoles هنا

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'password',
        'avatar',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Tickets créés par cet utilisateur
    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }

    // Tickets assignés à cet utilisateur (technicien)
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }
    // Conversations li fihom l-user howa user_one
    public function conversationsAsUserOne()
    {
        return $this->hasMany(Conversation::class, 'user_one_id');
    }

    // Conversations li fihom l-user howa user_two
    public function conversationsAsUserTwo()
    {
        return $this->hasMany(Conversation::class, 'user_two_id');
    }

    // Method sahla bach tjib ga3 conversations dyal l-user (b juj)
    public function conversations()
    {
        return Conversation::where('user_one_id', $this->id)
                           ->orWhere('user_two_id', $this->id);
    }
}