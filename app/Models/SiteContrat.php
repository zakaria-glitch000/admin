<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteContrat extends Model
{
    use HasFactory;

    protected $table = 'site_contrats';

    protected $fillable = [
        'site_id',
        'numero_contrat',
        'date_debut',
        'date_fin',
    ];

    public function site()
    {
        return $this->belongsTo(ClientSite::class, 'site_id');
    }
}