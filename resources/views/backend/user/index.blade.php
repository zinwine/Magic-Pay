@extends('backend.layouts.app')
@section('title', 'User View')   
@section('user-active', 'mm-active')   
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-display2 icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>@yield('page_title', 'Users')</div>
        </div>   
    </div>
</div> 


<div class="pt-3">
    <a href="{{route('admin.user.create')}}" class="btn btn-primary"><i class="fas fa-plus-circle"> Create User</i></a>
</div>

<div class="content pt-3">
    <div class="card">
        <div class="card-body">
            <table id="users" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Ip Address</th>
                        <th>User Agent</th>
                        <th>LoginAt</th>
                        <th>CreatedAt</th>
                        <th>Upadated</th>
                        <th class="no_sort">Action</th>
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
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if(token){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF_TOKEN' : token.content
                    }
                })
            }
            var table = $('#users').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/admin/user/datatable/ssd",
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'ip',
                        name: 'ip'
                    },
                    {
                        data: 'user_agent',
                        name: 'user_agent'
                    },
                    {
                        data: 'login_at',
                        name: 'login_at'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                columnDefs: [{
                    target: "no_sort",
                    sortable: false,
                    searchable: false
                }]
            });

            //**************** Delete Function *************
            $(document).on('click', '.delete_user', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                var token = $(this).data('token');
                    Swal.fire({
                        title: 'Do you want to delete?',
                        showCancelButton: true,
                        confirmButtonText: `Confirm`,
                    }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/admin/user/' + id,
                                type: 'DELETE',
                                success: function(){
                                    table.ajax.reload();
                                    Swal.fire('Deleted!', '', 'success')
                                }
                            });
                        }
                    })
            })
        });

    </script>
@endsection
