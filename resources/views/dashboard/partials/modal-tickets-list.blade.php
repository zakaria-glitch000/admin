<div class="table-responsive">
    <table class="table align-middle table-nowrap table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Référence</th>
                <th>Date Création</th>
                <th>Temps Passé</th>
                <th>Client</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th class="text-center">Assigné à</th>
                <th>Échéance SLA</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                @php
                    $colorMap = [
                        'Orange' => 'warning',
                        'Vert' => 'success',
                        'Rouge' => 'danger',
                        'Gris' => 'secondary',
                        'Bleu' => 'primary',
                        'Bleu Ciel' => 'info'
                    ];

                    $pColor = $ticket->priority?->couleur;
                    $priorityBg = isset($colorMap[$pColor]) ? $colorMap[$pColor] : 'info';

                    $sColor = $ticket->status?->couleur;
                    $statusBg = isset($colorMap[$sColor]) ? $colorMap[$sColor] : 'warning';

                    $dernierHistorique = $ticket->histories?->sortByDesc('created_at')->first();
                @endphp
                {{-- استعملنا نفس الطريقة المباشرة اللي خدامة في الداشبورد الرئيسية بدون تعقيد --}}
                <tr onclick="if(event.target.tagName !== 'SELECT' && event.target.tagName !== 'OPTION') { window.location='{{ route('tickets.show', $ticket) }}'; }" style="cursor: pointer;" class="clickable-row">
                    <td>
                        <span class="text-body fw-bold">{{ $ticket->reference }}</span>
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ $ticket->created_at?->format('d/m/Y H:i') }}
                        </small>
                    </td>
                    <td>
                        @if($dernierHistorique && !empty($dernierHistorique->temps_resolution))
                            <span class="badge bg-light text-dark border">
                                <i class="bx bx-time text-success me-1"></i> {{ $dernierHistorique->temps_resolution }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($ticket->client)
                            {{ $ticket->client->nom_societe }}
                        @elseif($ticket->site && $ticket->site->client)
                            {{ $ticket->site->client->nom_societe }} <small class="text-muted">({{ $ticket->site->nom }})</small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ Str::limit($ticket->titre, 30) }}</td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $ticket->category->nom ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $priorityBg }}">
                            {{ $ticket->priority->nom ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $statusBg }}">
                            {{ $ticket->status->nom ?? '-' }}
                        </span>
                    </td>
                    
                    <td class="text-center">
                        @if(auth()->user()->hasRole('Admin') || auth()->user()->email === 'admin@gmail.com')
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
                            {{ $ticket->assignedTo?->name ?? $ticket->assignedTo?->nom ?? 'Non assigné' }}
                        @endif
                    </td>

                    <td>
                        @if($ticket->date_echeance_sla)
                            <small class="{{ \Carbon\Carbon::parse($ticket->date_echeance_sla)->isPast() ? 'text-danger fw-bold' : 'text-muted' }}">
                                {{ \Carbon\Carbon::parse($ticket->date_echeance_sla)->format('d/m/Y H:i') }}
                            </small>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">Aucun ticket trouvé dans cette section.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>