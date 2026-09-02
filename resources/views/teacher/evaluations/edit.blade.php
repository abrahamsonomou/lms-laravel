@extends('layouts.dashboard')

@section('title', 'Éditer — ' . $evaluation->titre)
@section('page-title', 'Éditer — ' . $evaluation->titre)
@section('page-subtitle', 'Modifiez les paramètres de l\'évaluation.')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.evaluations.update', $evaluation) }}">
                @method('PUT')
                @include('teacher.evaluations._form', ['cancelUrl' => route('teacher.formations.evaluations.index', $evaluation->formation_id)])
            </form>
        </div>
    </div>
@endsection
