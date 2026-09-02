@extends('layouts.dashboard')

@section('title', 'Nouvelle organisation')
@section('page-title', 'Nouvelle organisation')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.organisations.store') }}" enctype="multipart/form-data">
                @include('backoffice.organisations._form')
            </form>
        </div>
    </div>
@endsection
