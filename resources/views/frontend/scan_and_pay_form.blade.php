@extends('frontend.layouts.app')
@section('page-name', 'Scan & Pay Form')
@section('content')
<div class="transfer"> 
    <div class="card">
        <div class="card-body">
            @include('frontend.layouts.flash')
            <form action="{{url("scan_and_pay/confirm")}}" method="GET" autocomplete="off" id="transfer_form">
                <input type="hidden" name="hash_value" class="hash_value" value="">
                <input type="hidden" name="to_phone" class="to_phone" value="{{$to_account->phone}}">
                <div class="mb-3">
                    <label class="mb-1">From</label>
                    <p class="mb-1">{{$from_account->name}}</p>
                    <p class="mb-1">{{$from_account->phone}}</p>
                </div>
                <div class="mb-3">
                    <label class="mb-1">To</label>
                    <p class="mb-1">{{$to_account->name}}</p>
                    <p class="mb-1">{{$to_account->phone}}</p>
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
