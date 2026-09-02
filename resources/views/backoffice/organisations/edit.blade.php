@extends('layouts.dashboard')

@section('title', 'Modifier une organisation')
@section('page-title', 'Modifier une organisation')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.organisations.update', $organisation) }}" enctype="multipart/form-data">
                @method('PUT')
                @include('backoffice.organisations._form')
            </form>
        </div>
    </div>
@endsection
