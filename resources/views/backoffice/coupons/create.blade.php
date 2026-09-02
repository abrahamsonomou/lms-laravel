@extends('layouts.dashboard')

@section('title', 'Nouveau coupon')
@section('page-title', 'Nouveau coupon')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.coupons.store') }}">
                @include('backoffice.coupons._form')
            </form>
        </div>
    </div>
@endsection
