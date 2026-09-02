@extends('layouts.dashboard')

@section('title', 'Nouvelle formation')
@section('page-title', 'Nouvelle formation')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.formations.store') }}" enctype="multipart/form-data">
                @csrf
                @include('catalogue._formation-fields')

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('admin.formations.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
