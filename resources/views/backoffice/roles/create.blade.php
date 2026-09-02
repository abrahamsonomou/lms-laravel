@extends('layouts.dashboard')

@section('title', 'Nouveau rôle')
@section('page-title', 'Nouveau rôle')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.roles.store') }}">
                @include('backoffice.roles._form')
            </form>
        </div>
    </div>
@endsection
