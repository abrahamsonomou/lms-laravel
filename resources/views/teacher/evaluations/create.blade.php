@extends('layouts.dashboard')

@section('title', 'Nouvelle évaluation')
@section('page-title', 'Nouvelle évaluation')
@section('page-subtitle', $formation->titre)

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.formations.evaluations.store', $formation) }}">
                @include('teacher.evaluations._form', ['cancelUrl' => route('teacher.formations.evaluations.index', $formation)])
            </form>
        </div>
    </div>
@endsection
