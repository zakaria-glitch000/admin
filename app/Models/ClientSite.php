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

    // Relation m3a L-Historique dyal Les Contrats (Table jdida: site_contrats)
    public function contrats()
    {
        return $this->hasMany(SiteContrat::class, 'site_id');
    }
}