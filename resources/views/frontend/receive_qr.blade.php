@extends('frontend.layouts.app')
@section('page-name', 'Rececive QR')
@section('content')
<div class="receive_qr">

    <div class="card mb-3 my-card">
        <div class="card-body">
            <p class="text-center">QR Scan </p>
            <div class="text-center">
                {{ QrCode::size(200)->generate($user->phone) }}            
            </div>
            <p class="text-center text-muted my-1"><strong>{{$user->name}}</strong></p>
            <p class="text-center text-muted mb-1">{{$user->phone}}</p>
        </div>
</div>
@endsection

