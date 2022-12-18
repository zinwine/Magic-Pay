@extends('frontend.layouts.app')
@section('page-name', 'Wallet')
@section('content')
<div class="wallet">

    <div class="card mb-3 my-card">
        <div class="card-body pr-0">
            <div class="mb-3"><span>Balance</span>
             <h4>{{number_format($user->wallet->amount ? $user->wallet->amount : 0)}} MMK</h4></div>
            <div class="mb-3"><span>Account Number</span>
             <h5>{{$user->wallet->account_number ? $user->wallet->account_number : '-'}}</h5></div>
            <div class="mb-3">
                {{-- <span>Account Name</span> --}}
             <p>{{$user->name}}</p></div>
            
            
            
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
             //**************** Logout Function *************
             $(document).on('click', '.logout-profile', function(e){
                e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure, you want to logout?',
                        showCancelButton: true,
                        confirmButtonText: `Confirm`,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{route('logout')}}",
                                type: 'POST',
                                success: function(){
                                    window.location.replace("{{route('home')}}");
                                }
                            });
                        }
                    })
            })
        })
    </script>
@endsection
