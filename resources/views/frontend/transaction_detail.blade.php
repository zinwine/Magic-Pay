@extends('frontend.layouts.app')
@section('page-name', 'Transaction Detail')
@section('content')
<div class="transaction_deatil">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <img src="{{asset('img/checked.png')}}" alt="">
                </div>
                @if (session('transfer_success'))
                    <div class="text-center alert alert-success fade show" role="alert">
                        {{session('transfer_success')}}
                      </div>                    
                @endif
                <h6 class="text-center {{$transaction->type == 2 ? 'text-danger' : 'text-success'}}">{{number_format($transaction->amount)}} <small>MMK</small></h6>

                
                <div class="d-flex justify-content-between">
                    <p class="text-mute mb-0">Trx Id</p>
                    <p class="text-mute mb-0">{{$transaction->trx_id}}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="text-mute mb-0">Refenerce Number</p>
                    <p class="text-mute mb-0">{{$transaction->ref_no}}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="text-mute mb-0">Type</p>
                    <p class="text-mute mb-0">
                        @if ($transaction->type == 2)    
                            <span class="badge badge-pill badge-danger">Expense</span>
                        @elseif ($transaction->type == 1)
                            <span class="badge badge-pill badge-success">Income</span>
                        @endif
                    </p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="text-mute mb-0">Amount</p>
                    <p class="text-mute mb-0">{{$transaction->amount}}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="text-mute mb-0">Date</p>
                    <p class="text-mute mb-0">{{$transaction->created_at}}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="text-mute mb-0">
                        @if ($transaction->type == 2)    
                            To
                        @elseif ($transaction->type == 1) 
                            From
                        @endif
                    </p>
                    <p class="text-mute mb-0">{{$transaction->source ? $transaction->source->name : '-'}}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="text-mute mb-0">Description</p>
                    <p class="text-mute mb-0">{{$transaction->description}}</p>
                </div>
            </div>
        </div>
</div>
@endsection




