@extends('layouts.dashboard')

@section('title', 'Nouveau plan')
@section('page-title', 'Nouveau plan')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.plans.store') }}">
                @include('backoffice.plans._form', ['cancelUrl' => route('admin.plans.index')])
            </form>
        </div>
    </div>
@endsection
