@extends('layouts.dashboard')

@section('title', 'Nouvelle catégorie')
@section('page-title', 'Nouvelle catégorie')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                @include('backoffice.categories._form')
            </form>
        </div>
    </div>
@endsection
