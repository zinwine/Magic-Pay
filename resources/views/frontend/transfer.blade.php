@extends('frontend.layouts.app')
@section('page-name', 'Transfer')
@section('content')
<div class="transfer">
 
    <div class="card">
        <div class="card-body">
            @include('frontend.layouts.flash')
            <form action="{{url("transfer/confirm")}}" method="GET" autocomplete="off" id="transfer_form">
                <input type="hidden" name="hash_value" class="hash_value" value="">

                <div class="mb-3">
                    <label class="mb-1">From</label>
                    <p class="mb-1">{{$user->name}}</p>
                    <p class="mb-1">{{$user->phone}}</p>
                </div>
                <div class="form-group mb-3">                    
                    <label for="to_phone" class="mb-1">To <span class="to_account_info text-success"></span></label>
                        <div class="input-group">
                            <input type="text" name="to_phone" id="to_phone" class="form-control to_phone" value="{{old('to_phone')}}">
                            <div class="input-group-append">
                                <span class="input-group-text btn btn_theme verify_btn"><i class="fas fa-check-circle"></i></span>
                            </div>
                        </div>
                        @error('to_phone')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="amount" class="mb-1">Amount (MMK)</label>
                    <input type="number" name="amount" id="amount" class="form-control to_amount" value="{{old('amount')}}">
                    @error('amount')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="description" class="mb-1">Description</label>
                    <textarea name="description" id="description" class="form-control to_description" value="{{old('description')}}"></textarea>
                </div>
                <button type="submit" class="submit_btn btn btn-block btn_theme mt-4">Continue</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $('.verify_btn').on('click', function(){
                var phone = $('.to_phone').val();
                // console.log(phone);
                $.ajax({
                    url: '/to-account-verify?phone=' + phone,
                    type: 'GET',
                    success: function(res){
                        if(res.status ==  'success'){
                            var name = '(' + res.data.name + ')';
                            $('.to_account_info').text(name);
                        }else{
                            var message = '(' + res.message + ')';
                            $('.to_account_info').text(message);
                        }
                    }
                });
            })

            $('.submit_btn').on('click', function(e){
                e.preventDefault();
                var to_phone = $('.to_phone').val();
                var to_amount = $('.to_amount').val();
                var to_description = $('.to_description').val();

                $.ajax({
                    url: `/transfer_hash?phone=${to_phone}&amount=${to_amount}&description=${to_description}`,
                    type: 'GET',
                    success: function(res){
                        if(res.status ==  'success'){
                            $('.hash_value').val(res.data);
                            $('#transfer_form').submit();
                        }
                    }
                });
            })
        })
    </script>
@endsection
