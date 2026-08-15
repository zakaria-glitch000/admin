@extends('layouts.master')

@section('title') Nouveau Ticket @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Créer un Nouveau Ticket</h4>
            <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <!-- Client -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Client *</label>
                            <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                                <option value="">Sélectionner un client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->nom_societe }}</option>
                                @endforeach
                            </select>
                            @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Site -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Site du Client</label>
                            <select name="client_site_id" id="client_site_id" class="form-select">
                                <option value="">Choisir un site...</option>
                            </select>
                        </div>

                        <!-- Machine -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Machine concernée</label>
                            <select name="machine_id" id="machine_id" class="form-select">
                                <option value="">Choisir une machine...</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Catégorie -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Catégorie *</label>
                            <select name="ticket_category_id" class="form-select @error('ticket_category_id') is-invalid @enderror" required>
                                <option value="">Sélectionner...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Priorité -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Priorité *</label>
                            <select name="ticket_priority_id" class="form-select @error('ticket_priority_id') is-invalid @enderror" required>
                                <option value="">Sélectionner...</option>
                                @foreach($priorities as $priority)
                                    <option value="{{ $priority->id }}">{{ $priority->nom }} (SLA: {{ $priority->delai_sla_heures }}h)</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Source -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Source *</label>
                            <select name="source" class="form-select @error('source') is-invalid @enderror" required>
                                <option value="telephone">Téléphone</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="email">Email</option>
                                <option value="sur_place">Sur place</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Titre du Ticket *</label>
                        <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}" placeholder="Ex: Panne de connexion sur imprimante XL" required>
                        @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description détaillée *</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Créer le Ticket</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('client_id').addEventListener('change', function() {
        let clientId = this.value;
        let siteSelect = document.getElementById('client_site_id');
        let machineSelect = document.getElementById('machine_id');

        siteSelect.innerHTML = '<option value="">Chargement...</option>';
        machineSelect.innerHTML = '<option value="">Chargement...</option>';

        if(clientId) {
            fetch(`/api/clients/${clientId}/data`)
                .then(response => response.json())
                .then(data => {
                    console.log('Données reçues mn l-API:', data);

                    // Sites
                    siteSelect.innerHTML = '<option value="">Choisir un site...</option>';
                    if(data.sites && data.sites.length > 0) {
                        data.sites.forEach(site => {
                            let siteName = site.nom_site || site.nom || site.name || 'Site #' + site.id;
                            siteSelect.innerHTML += `<option value="${site.id}">${siteName}</option>`;
                        });
                    } else {
                        siteSelect.innerHTML = '<option value="">Aucun site disponible</option>';
                    }

                    // Machines
                    machineSelect.innerHTML = '<option value="">Choisir une machine...</option>';
                    if(data.machines && data.machines.length > 0) {
                        data.machines.forEach(machine => {
                            let catName = 'Machine';
                            
                            // Vérification dyal ga3 les formats mhtamalin d la relation
                            if (machine.category && machine.category.nom) {
                                catName = machine.category.nom;
                            } else if (machine.machine_category && machine.machine_category.nom) {
                                catName = machine.machine_category.nom;
                            } else if (machine.machineCategory && machine.machineCategory.nom) {
                                catName = machine.machineCategory.nom;
                            } else if (machine.nom_categorie) {
                                catName = machine.nom_categorie;
                            }

                            let serialNum = machine.numero_serie ? ` - S/N: ${machine.numero_serie}` : '';
                            
                            machineSelect.innerHTML += `<option value="${machine.id}">${catName}${serialNum}</option>`;
                        });
                    } else {
                        machineSelect.innerHTML = '<option value="">Aucune machine disponible</option>';
                    }
                })
                .catch(error => {
                    console.error('Erreur AJAX:', error);
                    siteSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    machineSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        } else {
            siteSelect.innerHTML = '<option value="">Choisir un site...</option>';
            machineSelect.innerHTML = '<option value="">Choisir une machine...</option>';
        }
    });
</script>
@endpush
@endsection