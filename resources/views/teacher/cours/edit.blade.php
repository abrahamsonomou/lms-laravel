@extends('layouts.dashboard')

@section('title', 'Éditer le cours')
@section('page-title', 'Éditer — ' . $cours->titre)

@section('content')
    <div class="row">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.cours.update', $cours) }}">
                        @method('PUT')
                        @include('teacher.cours._form', ['cancelUrl' => route('teacher.formations.cours.index', $cours->formation_id)])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
