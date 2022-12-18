@extends('frontend.layouts.app')
@section('page-name', 'Transaction')
@section('content')
<div class="transaction">

    <div class="card mb-2">
        <div class="card-body p-2">
            <h6><i class="fas fa-filter"></i> Filter</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Date</label>
                        </div>
                        <input class="date form-control" value="{{request()->date}}" placeholder="All" type="text"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <div class="input-group-prepend">
                          <label class="input-group-text">Type</label>
                        </div>
                        <select class="custom-select type">
                          <option value="">All</option>
                          <option value="1" @if(request()->type == 1) selected @endif>Income</option>
                          <option value="2" @if(request()->type == 2) selected @endif>Expense</option>
                        </select>
                      </div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="p-2">Transactions</h5>

    <div class="infinite-scroll">
        @foreach ($transactions as $transaction)
        <a href="{{url('transation/'.$transaction->trx_id)}}">
            <div class="card mb-2">
                <div class="card-body p-3">
                    <h6>Trx Id : {{$transaction->trx_id}}</h6>
                    <div class="d-flex justify-content-between">
                        <p class="text-mute mb-1">
                            @if ($transaction->type == 2)    
                            To               
                            {{$transaction->source ? $transaction->source->name : '-'}}
                            @else 
                            From
                            {{$transaction->source ? $transaction->source->name : '-'}}
                        @endif
                        </p>
                        <p class="mb-1 {{$transaction->type == 2 ? 'text-danger' : 'text-success'}}">{{$transaction->amount}} <small>MMK</small></p>
                    </div>
                    <p class="text-mute mb-0">{{$transaction->created_at}}</p>
                </div>
            </div>
        @endforeach
        </a>
        {{$transactions->links()}}    
    </div>
</div>
@endsection
@section('script')
    <script>
        $('ul.pagination').hide();
        $(function() {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });
        $('.type').change(function(){
            var type = $('.type').val();
            var date = $('.date').val();

            history.pushState(null, '', `?type=${type}&date=${date}`);
            location.reload();
        })
        $('.date').daterangepicker({
            "singleDatePicker": true,
            "autoApply": false,
            "autoUpdateInput": false,
            "locale": {
                "format": "YYYY-MM-DD"
            }
        });
        $('.date').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            var date = $('.date').val();
            var type = $('.type').val();
            history.pushState(null, '', `?type=${type}&date=${date}`);
            location.reload();

        });
        $('.date').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            var date = $('.date').val();
            var type = $('.type').val();
            history.pushState(null, '', `?type=${type}&date=${date}`);
            location.reload();

        });
    </script>
@endsection
