@extends('layouts.dashboard')

@section('title', 'Modifier un rôle')
@section('page-title', 'Modifier un rôle')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                @method('PUT')
                @include('backoffice.roles._form')
            </form>
        </div>
    </div>
@endsection
