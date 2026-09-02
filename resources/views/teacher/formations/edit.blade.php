@extends('layouts.dashboard')

@section('title', 'Modifier la formation')
@section('page-title', 'Modifier la formation')
@section('page-subtitle', $formation->titre)

@section('content')
    <div class="row">
        <div class="col-lg-10 col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.formations.update', $formation) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('catalogue._formation-fields')

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fe fe-save me-1"></i> Enregistrer
                            </button>
                            <a href="{{ route('teacher.formations.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
