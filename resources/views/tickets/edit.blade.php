@extends('layouts.master')

@section('title') Modifier Ticket {{ $ticket->reference }} @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Modifier Ticket: {{ $ticket->reference }}</h4>
            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-secondary btn-sm"><i class="bx bx-arrow-back me-1"></i> Annuler</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Client <span class="text-danger">*</span></label>
                            <select name="client_id" class="form-select" required>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ $ticket->client_id == $client->id ? 'selected' : '' }}>
                                        {{ $client->nom_societe }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Site</label>
                            <select name="client_site_id" class="form-select">
                                <option value="">-- Aucun site --</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}" {{ $ticket->client_site_id == $site->id ? 'selected' : '' }}>
                                        {{ $site->nom }} ({{ $site->ville }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Machine</label>
                            <select name="machine_id" class="form-select">
                                <option value="">-- Aucune machine --</option>
                                @foreach($machines as $machine)
                                    <option value="{{ $machine->id }}" {{ $ticket->machine_id == $machine->id ? 'selected' : '' }}>
                                        {{ $machine->marque }} {{ $machine->modele }} ({{ $machine->numero_serie }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select name="ticket_category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $ticket->ticket_category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Priorité SLA <span class="text-danger">*</span></label>
                            <select name="ticket_priority_id" class="form-select" required>
                                @foreach($priorities as $prio)
                                    <option value="{{ $prio->id }}" {{ $ticket->ticket_priority_id == $prio->id ? 'selected' : '' }}>
                                        {{ $prio->nom }} ({{ $prio->delai_sla_heures }}h)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Source <span class="text-danger">*</span></label>
                            <select name="source" class="form-select" required>
                                <option value="telephone" {{ $ticket->source == 'telephone' ? 'selected' : '' }}>Téléphone</option>
                                <option value="whatsapp" {{ $ticket->source == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                <option value="email" {{ $ticket->source == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="sur_place" {{ $ticket->source == 'sur_place' ? 'selected' : '' }}>Sur Place</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="titre" class="form-control" value="{{ old('titre', $ticket->titre) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" rows="5" class="form-control" required>{{ old('description', $ticket->description) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection