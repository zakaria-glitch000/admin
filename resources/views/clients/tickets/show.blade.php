@extends('layouts.master')

@section('title') Détails du Ticket @endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Ticket : {{ $ticket->reference }}</h4>
            <a href="{{ route('client.tickets.index') }}" class="btn btn-secondary btn-sm">Retour</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- معلومات التيكت الأساسية -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">{{ $ticket->titre ?? $ticket->sujet }}</h4>
                
                <div class="mb-3">
                    <span class="badge bg-{{ $ticket->priority->couleur ?? 'secondary' }} me-2">
                        Priorité : {{ $ticket->priority->nom ?? '-' }}
                    </span>
                    <span class="badge bg-{{ $ticket->status->couleur ?? 'info' }}">
                        Statut : {{ $ticket->status->nom ?? 'En cours' }}
                    </span>
                </div>

                <p class="text-muted" style="white-space: pre-line;">{{ $ticket->description }}</p>
            </div>
        </div>

        <!-- الردود والتعليقات -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-4">Discussion</h5>
                
                @forelse($ticket->comments->where('est_interne', false) as $comment)
                    <div class="d-flex mb-3 border-bottom pb-3">
                        <div class="flex-grow-1">
                            <h6 class="mt-0 mb-1">
                                {{ $comment->user->name ?? ($comment->user->nom ?? 'Utilisateur') }} 
                                <small class="text-muted float-end">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                            </h6>
                            <p class="text-muted mb-0" style="white-space: pre-line;">{{ $comment->message }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">Aucune discussion pour le moment.</p>
                @endforelse

                <!-- إضافة رد جديد من طرف الكليان -->
                <form action="{{ route('client.tickets.add-comment', $ticket->id) }}" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-3">
                        <textarea name="message" rows="3" class="form-control @error('message') is-invalid @enderror" required placeholder="Écrire une réponse..."></textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Répondre</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection