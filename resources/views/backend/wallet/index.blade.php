@extends('backend.layouts.app')
@section('title', 'User Wallet')   
@section('wallet-active', 'mm-active')   
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-wallet icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>@yield('page_title', 'Users Wallet')</div>
        </div>   
    </div>
</div> 

<div class="content pt-3">
    <div class="card">
        <div class="card-body">
            <table id="wallet" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Account Number</th>
                        <th>Account Person</th>
                        <th>Amount (MMK)</th>
                        <th>Created At</th>
                        <th>Upadated At</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>       
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#wallet').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/admin/wallet/datatable/ssd",
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'account_number',
                        name: 'account_number'
                    },
                    {
                        data: 'account_person',
                        name: 'account_person'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                ]
            });
        });

    </script>
@endsection
