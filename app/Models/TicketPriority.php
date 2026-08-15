<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPriority extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'couleur', 'delai_sla_heures'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'priority_id');
    }
}