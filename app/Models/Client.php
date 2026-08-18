<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Client extends Model
{
    use HasFactory, SoftDeletes;

protected $fillable = [
        'user_id',
        'nom_societe',
        'raison_sociale', // <-- Zidha hna
        'ice',            // <-- Zidha hna
        'secteur_activite',
        'telephone_principal',
        'email',
        'numero_devis',    
        'piece_jointe',    
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sites()
    {
        return $this->hasMany(ClientSite::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // <-- Zdnaha Hada Jdid -->
    public function documents()
    {
        return $this->hasMany(ClientDocument::class);
    }

    // --- Accessors li kaykono mndomin m3a Blade dyalk ---

    public function getComputedTotalSitesAttribute()
    {
        return $this->sites->count();
    }

    public function getComputedSousContratAttribute()
    {
        $now = Carbon::now()->startOfDay();
        return $this->sites->filter(function($site) use ($now) {
            if (!$site->date_debut_contrat || !$site->date_fin_contrat) {
                return false;
            }
            return $now->between(
                Carbon::parse($site->date_debut_contrat)->startOfDay(),
                Carbon::parse($site->date_fin_contrat)->endOfDay()
            );
        })->count();
    }

    public function getEtatContratLabelAttribute()
    {
        $totalSites = $this->computed_total_sites;
        if ($totalSites == 0) return 'Aucun site';

        $sousContratSites = $this->computed_sous_contrat;

        if ($sousContratSites == $totalSites) {
            return 'Sous Contrat (الكل)';
        } elseif ($sousContratSites == 0) {
            return 'Hors Contrat (الكل)';
        } else {
            return "Partiellement ({$sousContratSites}/{$totalSites})";
        }
    }

    public function getEtatContratColorAttribute()
    {
        $totalSites = $this->computed_total_sites;
        if ($totalSites == 0) return 'secondary';

        $sousContratSites = $this->computed_sous_contrat;

        if ($sousContratSites == $totalSites) {
            return 'success';
        } elseif ($sousContratSites == 0) {
            return 'danger';
        } else {
            return 'warning';
        }
    }
}