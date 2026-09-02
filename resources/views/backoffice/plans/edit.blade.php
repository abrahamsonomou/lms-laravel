@extends('layouts.dashboard')

@section('title', 'Modifier le plan')
@section('page-title', 'Modifier le plan')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
                @method('PUT')
                @include('backoffice.plans._form', ['cancelUrl' => route('admin.plans.index')])
            </form>
        </div>
    </div>
@endsection
