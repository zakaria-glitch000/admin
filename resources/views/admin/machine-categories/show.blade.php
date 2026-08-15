@extends('layouts.master')

@section('title') Détails Catégorie Machine @endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Détails de la Catégorie</h4>
                <table class="table table-bordered">
                    <tr><th>Nom</th><td>{{ $category->nom }}</td></tr>
                    <tr><th>Slug</th><td><code>{{ $category->slug }}</code></td></tr>
                </table>
                <a href="{{ route('admin.machine-categories.index') }}" class="btn btn-secondary w-100 mt-3">Retour</a>
            </div>
        </div>
    </div>
</div>
@endsection