@extends('frontend.layouts.app')
@section('page-name', 'Magic Pay')
@section('content')
<div class="home">
    <div class="col-md-12">
            <div class="row">
            <div class="profile mb-3 col-12">
                <img src="https://ui-avatars.com/api/?background=5842e3&color=fff&name={{$user->name}}" alt="">
                <h5 class="mt-1">{{$user->name}}</h5>
                <p class="text-muted mb-0">{{number_format($user->wallet->amount ? $user->wallet->amount : 0)}} MMK</p>
            </div>
            <div class="col-6 shotcut-box px-1">
                <div class="card">
                    <div class="card-body p-3">
                        <a href="{{url('scan_and_pay')}}">
                            <img src="{{asset('img/qr-code-scan.png')}}" alt="">
                            <span> Scan & Pay</span>
                        </a>
                    </div>
                </div>
            </div>
             <div class="col-6 shotcut-box px-1">
                <div class="card">
                    <div class="card-body p-3">
                        <a href="{{url('receive_qr')}}">
                            <img src="{{asset('img/qr.png')}}" alt="">
                            <span> Receive QR</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card mt-3 col-12">
                <div class="card-body">
                        <a href="{{route('transfer')}}" class="d-flex justify-content-between">
                            <span><img class="home-icon-img" src="{{asset('img/money-transfer.png')}}" alt=""> Transfer</span>
                            <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                        </a>
                    <hr>
                    <a href="{{route('wallet')}}" class="d-flex justify-content-between">
                        <span><img class="home-icon-img" src="{{asset('img/wallet.png')}}" alt=""> Wallet</span>
                        <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                    </a>
                    <hr>
                    <a href="{{route('transation')}}" class="d-flex justify-content-between">
                        <span><img class="home-icon-img" src="{{asset('img/transaction.png')}}" alt=""> Transation</span>
                        <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
