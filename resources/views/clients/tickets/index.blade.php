@extends('layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Mes Tickets</h4>
            <div class="page-title-right">
                <a href="{{ route('client.tickets.create') }}" class="btn btn-primary btn-sm">Ouvrir un ticket</a>
            </div>
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
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap w-100 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Référence</th>
                                <th>Sujet</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            <tr>
                                <td><span class="fw-bold">{{ $ticket->reference }}</span></td>
                                <td>{{ Str::limit($ticket->sujet ?? $ticket->titre, 35) }}</td>
                                <td>
                                    <span class="badge bg-{{ $ticket->status->couleur ?? 'info' }}">
                                        {{ $ticket->status->nom ?? 'En cours' }}
                                    </span>
                                </td>
                                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('client.tickets.show', $ticket->id) }}" class="btn btn-primary btn-sm"><i class="bx bx-show"></i> Consulter</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucun ticket trouvé.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Bootstrap Fixée -->
                <div class="row mt-3">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" role="status" aria-live="polite">
                            Affichage de {{ $tickets->firstItem() ?? 0 }} à {{ $tickets->lastItem() ?? 0 }} sur {{ $tickets->total() }} entrées
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers float-end">
                            {!! $tickets->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection