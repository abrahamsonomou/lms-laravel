@extends('layouts.dashboard')

@section('title', 'Modifier un établissement')
@section('page-title', 'Modifier un établissement')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.etablissements.update', $etablissement) }}">
                @method('PUT')
                @include('backoffice.etablissements._form')
            </form>
        </div>
    </div>
@endsection
