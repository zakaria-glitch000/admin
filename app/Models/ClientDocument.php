<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientDocument extends Model
{
    protected $fillable = ['client_id', 'type', 'nom_fichier', 'chemin'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}