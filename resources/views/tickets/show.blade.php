@extends('layouts.master')

@section('title') Ticket {{ $ticket->reference }} @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Ticket {{ $ticket->reference }}</h4>
            <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <!-- Colonne Gauche: Description, Comments & Historique -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $ticket->titre }}</h4>
                <p class="text-muted">{{ $ticket->created_at->format('d/m/Y H:i') }} par <strong>{{ $ticket->creator->nom ?? 'Système' }}</strong></p>
                <hr>
                <p class="card-text">{!! nl2br(e($ticket->description)) !!}</p>
            </div>
        </div>

        <!-- Section Commentaires -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Commentaires vers client</h4>

                @foreach($ticket->comments as $comment)
                    <div class="d-flex mb-3 p-3 rounded {{ $comment->est_interne ? 'bg-warning-subtle border-start border-warning border-4' : 'bg-light' }}">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h5 class="font-size-14 m-0">
                                    {{ $comment->user->nom ?? 'Utilisateur' }}
                                    @if($comment->est_interne)
                                        <span class="badge bg-warning text-dark ms-2 font-size-11">Interne / Action</span>
                                    @endif
                                </h5>
                                <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <p class="mt-2 mb-0">{!! nl2br(e($comment->message)) !!}</p>
                        </div>
                    </div>
                @endforeach

                <!-- Form Add Public Comment -->
                <form action="{{ route('tickets.add-comment', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <div class="mb-3">
                        <textarea name="message" class="form-control" rows="3" placeholder="Ajouter un commentaire..." required></textarea>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-primary" onclick="this.disabled=true; this.form.submit();">
                                <i class="bx bx-paper-plane me-1"></i> Envoyer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Section Historique des Statuts & Rapports -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"><i class="bx bx-history text-primary me-1"></i> Historique des Statuts & Rapports</h4>

                @if(isset($ticket->histories) && $ticket->histories->count() > 0)
                    <div class="timeline ps-2">
                        @foreach($ticket->histories as $history)
                            <div class="d-flex mb-3 pb-3 border-bottom position-relative">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="font-size-14 m-0 text-dark fw-bold">
                                            {{ $history->user->nom ?? 'Utilisateur' }}
                                        </h5>
                                        <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <p class="mt-2 mb-1 text-muted small">
                                        Statut changé de : 
                                        <span class="badge bg-secondary">{{ $history->ancienStatus->nom ?? 'Début' }}</span> 
                                        <i class="bx bx-right-arrow-alt align-middle mx-1"></i> 
                                        <span class="badge bg-success">{{ $history->nouveauStatus->nom ?? '-' }}</span>
                                    </p>
                                    
                                    <!-- Commentaire / Rapport -->
                                    @if(!empty($history->commentaire))
                                        <div class="p-2 mt-2 rounded bg-warning-subtle border-start border-warning border-3 text-dark small">
                                            <strong>Rapport :</strong> {!! nl2br(e($history->commentaire)) !!}
                                        </div>
                                    @endif

                                    <!-- Temps de Résolution choisi par le technicien -->
                                    @if(!empty($history->temps_resolution))
                                        <div class="mt-2 text-muted small">
                                            <i class="bx bx-time text-success me-1"></i> <strong>Temps Résolution:</strong> <span class="badge bg-light text-dark border">{{ $history->temps_resolution }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted fst-italic mb-0">Aucun historique de statut enregistré pour le moment.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Colonne Droite: Statut, Rapport, SLA & Informations -->
    <div class="col-xl-4">
        <!-- Update Statut, Temps & Rapport Interne -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Changer le Statut</h4>
                <form action="{{ route('tickets.update-status', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nouveau Statut</label>
                        <select name="ticket_status_id" class="form-select" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ $ticket->ticket_status_id == $status->id ? 'selected' : '' }}>{{ $status->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Zidna hna l-input dyal Temps de Résolution lli ghadi ykhtar wla ykteb l-technicien -->
                    <div class="mb-3">
                        <label class="form-label text-muted small">Temps de Résolution / Durée</label>
                        <input type="text" name="temps_resolution" class="form-control" placeholder="Ex: 30 min, 1h 15m...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Rapport / Action (Interne)</label>
                        <textarea name="commentaire" class="form-control" rows="3" placeholder="Qu'est-ce qui a été fait ?"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100" onclick="this.disabled=true; this.form.submit();">
                        <i class="bx bx-refresh me-1"></i> Mettre à jour le Statut
                    </button>
                </form>
            </div>
        </div>

        <!-- Informations Sidebar -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Informations Ticket</h4>
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Créé par:</th>
                        <td><span class="fw-semibold text-primary">{{ $ticket->creator->nom ?? 'Système' }}</span></td>
                    </tr>
                    <tr>
                        <th>Client:</th>
                        <td>
                            @if($ticket->client)
                                <a href="{{ route('clients.show', $ticket->client) }}" class="fw-semibold">{{ $ticket->client->nom_societe }}</a>
                            @elseif($ticket->site && $ticket->site->client)
                                <a href="{{ route('clients.show', $ticket->site->client) }}" class="fw-semibold">{{ $ticket->site->client->nom_societe }} ({{ $ticket->site->nom }})</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Machine:</th>
                        <td>
                            @if($ticket->machine)
                                <a href="{{ route('machines.show', $ticket->machine->id) }}" class="fw-semibold text-dark">
                                    {{ $ticket->machine->category->nom ?? 'Machine' }} (S/N: {{ $ticket->machine->numero_serie }})
                                </a>
                            @else
                                <span class="text-muted">Aucune machine</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Priorité:</th>
                        <td>
                            @if($ticket->priority)
                                @php
                                    $pName = strtolower($ticket->priority->nom ?? '');
                                    $badgeClass = 'bg-secondary';
                                    if(str_contains($pName, 'haute') || str_contains($pName, 'urgente') || str_contains($pName, 'high')) {
                                        $badgeClass = 'bg-danger';
                                    } elseif(str_contains($pName, 'moyenne') || str_contains($pName, 'medium')) {
                                        $badgeClass = 'bg-warning text-dark';
                                    } elseif(str_contains($pName, 'faible') || str_contains($pName, 'low')) {
                                        $badgeClass = 'bg-info text-dark';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $ticket->priority->nom }}</span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Catégorie:</th>
                        <td>{{ $ticket->category->nom ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Assigné à:</th>
                        <td>
                            @if(auth()->user()->hasRole('Admin') || auth()->user()->email === 'admin@gmail.com' || auth()->user()->can('ticket-edit'))
                                <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="assigned_to" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">-- Non assigné --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $ticket->assigned_to == $user->id ? 'selected' : '' }}>
                                                {{ $user->name ?? $user->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @else
                                @if($ticket->assignedTo)
                                    <span class="badge bg-success">{{ $ticket->assignedTo->nom ?? $ticket->assignedTo->name }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">Non assigné</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>SLA Limite:</th>
                        <td><small class="text-danger fw-bold">{{ $ticket->date_echeance_sla ? $ticket->date_echeance_sla->format('d/m/Y H:i') : '-' }}</small></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection