<?php

namespace App\Http\Controllers;

use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentController extends Controller
{
    public function download(TicketAttachment $attachment)
    {
        if (!Storage::disk('public')->exists($attachment->chemin)) {
            abort(404, 'Fichier introuvable sur le serveur.');
        }

        return Storage::disk('public')->download($attachment->chemin, $attachment->nom_fichier);
    }

    public function destroy(TicketAttachment $attachment)
    {
        // Supprimer le fichier physique
        if (Storage::disk('public')->exists($attachment->chemin)) {
            Storage::disk('public')->delete($attachment->chemin);
        }

        // Supprimer l'enregistrement f la BDD
        $attachment->delete();

        return back()->with('success', 'Pièce jointe supprimée avec succès.');
    }
}