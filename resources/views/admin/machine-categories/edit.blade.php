@extends('layouts.master')

@section('title') Modifier Catégorie Machine @endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Modifier: {{ $category->nom }}</h4>
                <form action="{{ route('admin.machine-categories.update', $category) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" value="{{ $category->nom }}" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection