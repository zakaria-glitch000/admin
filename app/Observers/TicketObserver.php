<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Models\TicketPriority;

class TicketObserver
{
    public function creating(Ticket $ticket): void
    {
        // 1. Génération de la Référence unique (ex: TCK-2026-000001)
        if (empty($ticket->reference)) {
            $year = date('Y');
            $lastTicket = Ticket::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
            $number = $lastTicket ? ((int) substr($lastTicket->reference, -6)) + 1 : 1;
            $ticket->reference = 'TCK-' . $year . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
        }

        // 2. Calcul du Délai SLA automatique
        if (empty($ticket->date_echeance_sla) && $ticket->ticket_priority_id) {
            $priority = TicketPriority::find($ticket->ticket_priority_id);
            if ($priority && $priority->delai_sla_heures) {
                $ticket->date_echeance_sla = now()->addHours($priority->delai_sla_heures);
            }
        }
    }

    public function updated(Ticket $ticket): void
    {
        // L'historique des statuts est géré directement dans le Controller pour capturer le feedback technique.
    }
}