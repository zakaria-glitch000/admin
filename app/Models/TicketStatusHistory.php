<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketStatusHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'ancien_status_id',
        'nouveau_status_id',
        'user_id',
        'commentaire',
        'temps_resolution',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Noms en français (M3a Controller)
    public function ancienStatus()
    {
        return $this->belongsTo(TicketStatus::class, 'ancien_status_id');
    }

    public function nouveauStatus()
    {
        return $this->belongsTo(TicketStatus::class, 'nouveau_status_id');
    }

    // Noms en anglais (Aliases)
    public function oldStatus()
    {
        return $this->ancienStatus();
    }

    public function newStatus()
    {
        return $this->nouveauStatus();
    }
}