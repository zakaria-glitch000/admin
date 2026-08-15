<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_site_id',
        'machine_category_id',
        'marque',
        'modele',
        'numero_serie',
        'date_installation',
        'date_fin_garantie',
        'statut',
    ];

    protected $casts = [
        'date_installation' => 'date',
        'date_fin_garantie' => 'date',
    ];

    public function site()
    {
        return $this->belongsTo(ClientSite::class, 'client_site_id');
    }

    public function category()
    {
        return $this->belongsTo(MachineCategory::class, 'machine_category_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}