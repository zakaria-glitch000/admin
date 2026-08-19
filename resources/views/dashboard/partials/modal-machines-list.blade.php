<div class="table-responsive">
    <table class="table align-middle table-nowrap table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>S/N (N° Série)</th>
                <th>Marque & Modèle</th>
                <th>Catégorie</th>
                <th>Date d'installation</th>
                <th>Client / Site</th>
                <th>Garantie</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($machines as $machine)
                <tr onclick="window.location.href='{{ route('machines.show', $machine->id) }}';" style="cursor: pointer;" title="Cliquez pour voir les détails">
                    <td>
                        <span class="text-primary fw-bold">
                            {{ $machine->numero_serie ?? 'N/A' }}
                        </span>
                    </td>
                    <td>{{ $machine->marque ?? '' }} - {{ $machine->modele ?? '' }}</td>
                    <td>
                        <span class="badge bg-soft-dark text-dark font-size-12">
                            {{ $machine->category->nom ?? '-' }}
                        </span>
                    </td>
                    <td>
                        {{ $machine->date_installation ? \Carbon\Carbon::parse($machine->date_installation)->format('d/m/Y') : '-' }}
                    </td>
                    <td>
                        @if($machine->site && $machine->site->client)
                            <div><strong>{{ $machine->site->client->nom_societe }}</strong></div>
                            <small class="text-muted">{{ $machine->site->nom }} ({{ $machine->site->ville ?? '' }})</small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($machine->date_fin_garantie)
                            @if($machine->date_fin_garantie->isPast())
                                <span class="badge bg-soft-danger text-danger">Expirée ({{ $machine->date_fin_garantie->format('d/m/Y') }})</span>
                            @else
                                <span class="badge bg-soft-success text-success">Sous garantie ({{ $machine->date_fin_garantie->format('d/m/Y') }})</span>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($machine->statut == 'actif')
                            <span class="badge bg-success font-size-12">Actif</span>
                        @elseif($machine->statut == 'hors_service')
                            <span class="badge bg-danger font-size-12">Hors Service</span>
                        @else
                            <span class="badge bg-secondary font-size-12">Remplacé</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Aucune machine enregistrée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>