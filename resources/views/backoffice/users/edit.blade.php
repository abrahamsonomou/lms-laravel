@extends('layouts.dashboard')

@section('title', 'Modifier un utilisateur')
@section('page-title', 'Modifier un utilisateur')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                @method('PUT')
                @include('backoffice.users._form')
            </form>
        </div>
    </div>
@endsection
