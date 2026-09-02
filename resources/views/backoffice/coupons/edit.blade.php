@extends('layouts.dashboard')

@section('title', 'Modifier un coupon')
@section('page-title', 'Modifier un coupon')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
                @method('PUT')
                @include('backoffice.coupons._form')
            </form>
        </div>
    </div>
@endsection
