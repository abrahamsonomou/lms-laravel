@extends('layouts.dashboard')

@section('title', 'Nouveau cours')
@section('page-title', 'Nouveau cours')
@section('page-subtitle', $formation->titre)

@section('content')
    <div class="row">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.formations.cours.store', $formation) }}">
                        @include('teacher.cours._form', ['cancelUrl' => route('teacher.formations.cours.index', $formation)])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
