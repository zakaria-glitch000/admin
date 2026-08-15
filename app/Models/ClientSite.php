<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'nom',
        'adresse',
        'ville',
        'numero_contrat',      // <-- ضروري تزاد هنا
        'date_debut_contrat',  // <-- ضروري تزاد هنا
        'date_fin_contrat',    // <-- ضروري تزاد هنا
        'contact_nom',
        'contact_telephone',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}