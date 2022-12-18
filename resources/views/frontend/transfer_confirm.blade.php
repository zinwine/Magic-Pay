@extends('frontend.layouts.app')
@section('page-name', 'Transfer Confirmation')
@section('content')
<div class="transfer">
 
    <div class="card">
        <div class="card-body">
            @include('frontend.layouts.flash')
            <form action="{{url("transfer/complete")}}" method="POST" id="transfer_confirm">
                @csrf
                <input type="hidden" name="hash_value" value="{{$hash_value}}">
                <input type="hidden" name="to_phone" value="{{$to_account->phone}}">
                <input type="hidden" name="amount" value="{{$to_amount}}">
                <input type="hidden" name="to_description" value="{{$to_description}}">
                
                <div class="mb-3">
                    <label class="mb-1">From</label>
                    <p class="mb-1">{{$from_account->name}}</p>
                    <p class="mb-1">{{$from_account->phone}}</p>
                </div>
                <div class="form-group mb-3">
                    <label for="to_phone" class="mb-1">To</label>
                    <p class="mb-1 text-muted">{{$to_account->name}}</p>
                    <p class="mb-1 text-muted">{{$to_account->phone}}</p>
                </div>
                <div class="form-group mb-3">
                    <label for="amount" class="mb-1">Amount (MMK)</label>
                    <p class="mb-1 text-muted">{{number_format($to_amount)}}</p>
                </div>
                <div class="form-group mb-3">
                    <label for="description" class="mb-1">Description</label>
                    <p class="mb-1 text-muted">{{$to_description}}</p>
                </div>
                <button type="submit" class="confirm_btn btn btn-block btn_theme mt-4">Confirm</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $('.confirm_btn').on('click', function(e){
                e.preventDefault();
                Swal.fire({
                    title: '<strong>Please fill your password!</strong>',
                    // icon: 'info',
                    html: '<input type="password" class="form-control text-center password confirm_password" autofocus>',
                    showCloseButton: true,
                    showCancelButton: true,
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        var password = $('.confirm_password').val();
                        $.ajax({
                            url: '/account_confirm_password?password=' + password,
                            type: 'GET',
                            success: function(res){
                                if(res.status ==  'success'){
                                    $('#transfer_confirm').submit();
                                }else{
                                    Swal.fire({
                                        icon: 'error',
                                        // title: 'Sorry...',
                                        text: res.message,
                                    })
                                }
                            }
                        });
                    }
                })

                
            });
        });
    </script>
@endsection