@extends('layouts.dashboard')

@section('title', 'Nouvel établissement')
@section('page-title', 'Nouvel établissement')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.etablissements.store') }}">
                @include('backoffice.etablissements._form')
            </form>
        </div>
    </div>
@endsection
