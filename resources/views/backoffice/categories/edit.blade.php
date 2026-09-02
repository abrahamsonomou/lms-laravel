@extends('layouts.dashboard')

@section('title', 'Modifier la catégorie')
@section('page-title', 'Modifier la catégorie')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', $categorie) }}" enctype="multipart/form-data">
                @method('PUT')
                @include('backoffice.categories._form')
            </form>
        </div>
    </div>
@endsection
