@extends('layouts.master')

@section('title') Fiche Client - {{ $client->nom_societe }} @endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Fiche Client: {{ $client->nom_societe }}</h4>
            <div>
                <!-- زر تعديل الكلاينت -->
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editClientModal">
                    <i class="bx bx-pencil me-1"></i> Modifier le Client
                </button>

                <!-- زر حذف الكلاينت -->
                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('متأكد أنك تريد حذف هذا العميل نهائياً؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bx bx-trash me-1"></i> Supprimer
                    </button>
                </form>

                <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-sm"><i class="bx bx-arrow-back me-1"></i> Retour</a>
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
    <!-- Infos Client & Formulaire -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Coordonnées</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><strong>Secteur:</strong> {{ $client->secteur_activite ?? 'N/A' }}</li>
                    <li class="mb-2"><strong>Téléphone:</strong> {{ $client->telephone_principal }}</li>
                    <li class="mb-2"><strong>Email:</strong> {{ $client->email ?? 'N/A' }}</li>
                    <li class="mb-2"><strong>Date Creation:</strong> {{ $client->created_at->format('d/m/Y') }}</li>
                </ul>
                @if($client->notes)
                    <hr>
                    <h6>Notes:</h6>
                    <p class="text-muted font-size-13 mb-0">{{ $client->notes }}</p>
                @endif
            </div>
        </div>

        <!-- Formulaire Ajouter un Site / Succursale -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bx bx-map-pin me-1"></i> Ajouter un Site / Succursale</h5>
                <form action="{{ route('clients.sites.store', $client) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="nom" class="form-control form-control-sm" placeholder="Nom du site (ex: Magasin Maarif)" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="ville" class="form-control form-control-sm" placeholder="Ville (ex: Casablanca)" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="adresse" class="form-control form-control-sm" placeholder="Adresse complète" required>
                    </div>
                    <!-- حقول العقد الجديدة -->
                    <div class="mb-2">
                        <input type="text" name="numero_contrat" class="form-control form-control-sm" placeholder="Numéro de Contrat">
                    </div>
                    <div class="mb-2">
                        <label class="form-label font-size-11 text-muted mb-0">Date début contrat</label>
                        <input type="date" name="date_debut_contrat" class="form-control form-control-sm">
                    </div>
                    <div class="mb-2">
                        <label class="form-label font-size-11 text-muted mb-0">Date fin contrat</label>
                        <input type="date" name="date_fin_contrat" class="form-control form-control-sm">
                    </div>
                    <!-- نهاية حقول العقد -->
                    <div class="mb-2">
                        <input type="text" name="contact_nom" class="form-control form-control-sm" placeholder="Nom du responsable">
                    </div>
                    <div class="mb-3">
                        <input type="text" name="contact_telephone" class="form-control form-control-sm" placeholder="Tél du responsable">
                    </div>
                    <button type="submit" class="btn btn-success btn-sm w-100"><i class="bx bx-plus me-1"></i> Ajouter le Site</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sites & Tickets du Client -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#sites_tab" role="tab">
                            <i class="bx bx-store font-size-20 me-1"></i> Sites ({{ $client->sites->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tickets_tab" role="tab">
                            <i class="bx bx-ticket font-size-20 me-1"></i> Tickets ({{ $client->tickets->count() }})
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-3 text-muted">
                    <!-- Tab Sites -->
                    <div class="tab-pane active" id="sites_tab" role="tabpanel">
                        
                        <!-- أزرار التحكم (تصدير إكسيل + إظهار/إخفاء الأعمدة) لجدول المواقع -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <!-- زر Export Excel -->
                            <button type="button" class="btn btn-success btn-sm" onclick="exportTableToExcel('sitesTable', 'Sites_{{ $client->nom_societe }}')">
                                <i class="bx bx-file me-1"></i> Exporter Excel
                            </button>

                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-slider-alt me-1"></i> Afficher / Masquer Colonnes
                                </button>
                                <ul class="dropdown-menu p-3 shadow" style="min-width: 200px;" onclick="event.stopPropagation();">
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="col_nom" checked onchange="toggleColumn('sitesTable', 'nom', this)">
                                            <label class="form-check-label" for="col_nom">Nom du Site</label>
                                        </div>
                                    </li>
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="col_ville" checked onchange="toggleColumn('sitesTable', 'ville', this)">
                                            <label class="form-check-label" for="col_ville">Ville</label>
                                        </div>
                                    </li>
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="col_adresse" checked onchange="toggleColumn('sitesTable', 'adresse', this)">
                                            <label class="form-check-label" for="col_adresse">Adresse</label>
                                        </div>
                                    </li>
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="col_contrat" checked onchange="toggleColumn('sitesTable', 'contrat', this)">
                                            <label class="form-check-label" for="col_contrat">Contrat</label>
                                        </div>
                                    </li>
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="col_contact" checked onchange="toggleColumn('sitesTable', 'contact', this)">
                                            <label class="form-check-label" for="col_contact">Contact Sur Place</label>
                                        </div>
                                    </li>
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="col_machines" checked onchange="toggleColumn('sitesTable', 'machines', this)">
                                            <label class="form-check-label" for="col_machines">Machines</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="col_action" checked onchange="toggleColumn('sitesTable', 'action', this)">
                                            <label class="form-check-label" for="col_action">Action</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-sm align-middle mb-0" id="sitesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 18%;" data-column="nom">Nom du Site</th>
                                        <th style="width: 12%;" data-column="ville">Ville</th>
                                        <th style="width: 20%;" data-column="adresse">Adresse</th>
                                        <th style="width: 15%;" data-column="contrat">Contrat</th>
                                        <th style="width: 15%;" data-column="contact">Contact Sur Place</th>
                                        <th style="width: 10%;" data-column="machines">Machines</th>
                                        <th style="width: 10%;" class="text-center" data-column="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($client->sites as $site)
                                        <tr>
                                            <td data-column="nom"><strong>{{ $site->nom }}</strong></td>
                                            <td data-column="ville">{{ $site->ville }}</td>
                                            <td data-column="adresse"><small class="text-muted">{{ $site->adresse }}</small></td>
                                            <td data-column="contrat">
                                                <small>
                                                    <strong>{{ $site->numero_contrat ?? '-' }}</strong><br>
                                                    @if($site->date_debut_contrat && $site->date_fin_contrat)
                                                        @php
                                                            $today = \Carbon\Carbon::today();
                                                            $debut = \Carbon\Carbon::parse($site->date_debut_contrat);
                                                            $fin = \Carbon\Carbon::parse($site->date_fin_contrat);
                                                            $isSousContrat = $today->between($debut, $fin);
                                                        @endphp

                                                        <span class="text-muted d-block mb-1">
                                                            {{ $debut->format('d/m/Y') }} ➔ {{ $fin->format('d/m/Y') }}
                                                        </span>

                                                        @if($isSousContrat)
                                                            <span class="badge bg-success font-size-10">Sous contrat</span>
                                                        @else
                                                            <span class="badge bg-danger font-size-10">Hors contrat</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Pas de dates</span>
                                                    @endif
                                                </small>
                                            </td>
                                            <td data-column="contact"><small>{{ $site->contact_nom ?? '-' }} <br>({{ $site->contact_telephone ?? '-' }})</small></td>
                                            <td data-column="machines"><span class="badge bg-soft-primary text-primary">{{ $site->machines->count() }}</span></td>
                                            <td class="text-center" data-column="action">
                                                <!-- زر تعديل الفرع -->
                                                <button type="button" class="btn btn-outline-success btn-sm px-2 py-1" data-bs-toggle="modal" data-bs-target="#editSiteModal{{ $site->id }}" title="Modifier">
                                                    <i class="bx bx-pencil"></i>
                                                </button>

                                                <!-- زر حذف الفرع -->
                                                <form action="{{ route('clients.sites.destroy', $site->id) }}" method="POST" class="d-inline" onsubmit="return confirm('متأكد أنك تريد حذف هذا الفرع؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm px-2 py-1" title="Supprimer">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Modal Modification Site -->
                                        <div class="modal fade" id="editSiteModal{{ $site->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Modifier le Site: {{ $site->nom }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('clients.sites.update', $site->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body text-start">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nom du site <span class="text-danger">*</span></label>
                                                                <input type="text" name="nom" class="form-control" value="{{ $site->nom }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Ville <span class="text-danger">*</span></label>
                                                                <input type="text" name="ville" class="form-control" value="{{ $site->ville }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Adresse complète <span class="text-danger">*</span></label>
                                                                <input type="text" name="adresse" class="form-control" value="{{ $site->adresse }}" required>
                                                            </div>
                                                            <!-- حقول العقد في التعديل -->
                                                            <div class="mb-3">
                                                                <label class="form-label">Numéro de Contrat</label>
                                                                <input type="text" name="numero_contrat" class="form-control" value="{{ $site->numero_contrat }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Date début contrat</label>
                                                                <input type="date" name="date_debut_contrat" class="form-control" value="{{ $site->date_debut_contrat }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Date fin contrat</label>
                                                                <input type="date" name="date_fin_contrat" class="form-control" value="{{ $site->date_fin_contrat }}">
                                                            </div>
                                                            <!-- نهاية حقول العقد -->
                                                            <div class="mb-3">
                                                                <label class="form-label">Nom du responsable</label>
                                                                <input type="text" name="contact_nom" class="form-control" value="{{ $site->contact_nom }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Tél du responsable</label>
                                                                <input type="text" name="contact_telephone" class="form-control" value="{{ $site->contact_telephone }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Mettre à jour</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-3">Aucun site configuré pour ce client.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Tickets -->
                    <div class="tab-pane" id="tickets_tab" role="tabpanel">
                        
                        <!-- فلتر التذاكر التلقائي: الموقع + التواريخ -->
                        <form method="GET" action="{{ route('clients.show', $client->id) }}#tickets_tab" class="card bg-light border-0 p-3 mb-3">
                            <div class="row align-items-end g-2">
                                <!-- فلتر حسب الموقع -->
                                <div class="col-md-4">
                                    <label class="form-label font-size-12 mb-1">Site</label>
                                    <select name="site_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">Tous les sites</option>
                                        @foreach($client->sites as $site)
                                            <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>
                                                {{ $site->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- تاريخ البداية -->
                                <div class="col-md-3">
                                    <label class="form-label font-size-12 mb-1">Date Début</label>
                                    <input type="date" name="date_debut" class="form-control form-control-sm" value="{{ request('date_debut') }}" onchange="this.form.submit()">
                                </div>

                                <!-- تاريخ النهاية -->
                                <div class="col-md-3">
                                    <label class="form-label font-size-12 mb-1">Date Fin</label>
                                    <input type="date" name="date_fin" class="form-control form-control-sm" value="{{ request('date_fin') }}" onchange="this.form.submit()">
                                </div>

                                <!-- زر إعادة الضبط (Reset) -->
                                <div class="col-md-2">
                                    @if(request('site_id') || request('date_debut') || request('date_fin'))
                                        <a href="{{ route('clients.show', $client->id) }}#tickets_tab" class="btn btn-outline-secondary btn-sm w-100" title="Réinitialiser">
                                            <i class="bx bx-reset me-1"></i> Reset
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <!-- أزرار التحكم (تصدير إكسيل + إظهار/إخفاء الأعمدة) -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <!-- زر Export Excel -->
                            <button type="button" class="btn btn-success btn-sm" onclick="exportTableToExcel('ticketsTable', 'Tickets_{{ $client->nom_societe }}')">
                                <i class="bx bx-file me-1"></i> Exporter Excel
                            </button>

                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-slider-alt me-1"></i> Afficher / Masquer Colonnes
                                </button>
                                <ul class="dropdown-menu p-3 shadow" style="min-width: 200px;" onclick="event.stopPropagation();">
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="tck_ref" checked onchange="toggleColumn('ticketsTable', 'ref', this)">
                                            <label class="form-check-label" for="tck_ref">Référence</label>
                                        </div>
                                    </li>
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="tck_sujet" checked onchange="toggleColumn('ticketsTable', 'sujet', this)">
                                            <label class="form-check-label" for="tck_sujet">Sujet</label>
                                        </div>
                                    </li>
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="tck_statut" checked onchange="toggleColumn('ticketsTable', 'statut', this)">
                                            <label class="form-check-label" for="tck_statut">Statut</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="tck_date" checked onchange="toggleColumn('ticketsTable', 'date', this)">
                                            <label class="form-check-label" for="tck_date">Date</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-sm align-middle mb-0" id="ticketsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th data-column="ref">Référence</th>
                                        <th data-column="sujet">Sujet</th>
                                        <th data-column="statut">Statut</th>
                                        <th data-column="date">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $ticketsList = $client->tickets;

                                        // فلترة حسب الموقع إذا تم اختياره
                                        if(request()->filled('site_id')) {
                                            $ticketsList = $ticketsList->where('client_site_id', request('site_id'));
                                        }

                                        // فلترة حسب تاريخ البداية
                                        if(request()->filled('date_debut')) {
                                            $ticketsList = $ticketsList->where('created_at', '>=', request('date_debut') . ' 00:00:00');
                                        }

                                        // فلترة حسب تاريخ النهاية
                                        if(request()->filled('date_fin')) {
                                            $ticketsList = $ticketsList->where('created_at', '<=', request('date_fin') . ' 23:59:59');
                                        }
                                    @endphp

                                    @forelse($ticketsList as $tck)
                                        <tr onclick="window.location='{{ route('tickets.show', $tck) }}';" style="cursor: pointer;" title="Voir les détails du ticket">
                                            <td data-column="ref"><strong>{{ $tck->reference }}</strong></td>
                                            <td data-column="sujet">{{ Str::limit($tck->titre ?? $tck->sujet, 30) }}</td>
                                            <td data-column="statut"><span class="badge bg-{{ $tck->status->couleur ?? 'secondary' }}">{{ $tck->status->nom ?? $tck->statut ?? '-' }}</span></td>
                                            <td data-column="date">{{ $tck->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-3">Aucun ticket trouvé pour ces critères.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modification Client -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le Client: {{ $client->nom_societe }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('clients.update', $client->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label">Raison Sociale / Nom Société <span class="text-danger">*</span></label>
                        <input type="text" name="nom_societe" class="form-control" value="{{ $client->nom_societe }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone Principal <span class="text-danger">*</span></label>
                        <input type="text" name="telephone_principal" class="form-control" value="{{ $client->telephone_principal }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $client->email }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Secteur d'Activité</label>
                        <input type="text" name="secteur_activite" class="form-control" value="{{ $client->secteur_activite }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes / Remarques</label>
                        <textarea name="notes" rows="2" class="form-control">{{ $client->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SheetJS Library لـ Export Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<!-- JavaScripts -->
<script>
    function toggleColumn(tableId, columnName, checkbox) {
        let isChecked = checkbox.checked;
        let table = document.getElementById(tableId);
        if (!table) return;

        let th = table.querySelector(`thead th[data-column="${columnName}"]`);
        if (th) {
            th.style.display = isChecked ? "" : "none";
        }

        let cells = table.querySelectorAll(`tbody td[data-column="${columnName}"]`);
        cells.forEach((cell) => {
            cell.style.display = isChecked ? "" : "none";
        });
    }

    // دالة تصدير الجدول إلى ملف Excel مع استثناء الأعمدة المخفية وتاريخ اليوم
    function exportTableToExcel(tableId, filename = 'export') {
        let table = document.getElementById(tableId);
        if (!table) return;

        let cloneTable = table.cloneNode(true);
        let originalRows = table.querySelectorAll('tr');
        let cloneRows = cloneTable.querySelectorAll('tr');
        
        originalRows.forEach((origRow, index) => {
            let origCells = origRow.querySelectorAll('th, td');
            let cloneCells = cloneRows[index].querySelectorAll('th, td');
            
            origCells.forEach((cell, cellIndex) => {
                if (window.getComputedStyle(cell).display === 'none') {
                    cloneCells[cellIndex].remove();
                }
            });
        });

        // جلب تاريخ اليوم وتنسيقه (DD-MM-YYYY)
        let today = new Date();
        let day = String(today.getDate()).padStart(2, '0');
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let year = today.getFullYear();
        let dateStr = `${day}-${month}-${year}`;

        let wb = XLSX.utils.table_to_book(cloneTable, {sheet: "Data"});
        // دمج اسم الملف مع تاريخ اليوم
        XLSX.writeFile(wb, filename + '_' + dateStr + '.xlsx');
    }

    // تفعيل الـ Tab تلقائياً إيلا كان الـ URL فيه Hash
    document.addEventListener("DOMContentLoaded", function () {
        if (window.location.hash) {
            let activeTab = document.querySelector(`a[href="${window.location.hash}"]`);
            if (activeTab) {
                let tab = new bootstrap.Tab(activeTab);
                tab.show();
            }
        }
    });
</script>

@endsection