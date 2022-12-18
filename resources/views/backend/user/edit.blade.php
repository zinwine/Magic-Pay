@extends('backend.layouts.app')
@section('title', 'User Edit')   
@section('user-active', 'mm-active')   
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-display2 icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>@yield('page_title', 'Edit User')</div>
        </div>   
    </div>
</div>  


<div class="content pt-3">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">User Edit Form</h5>
            {{-- @include('backend.layouts.flash') --}}
            <form action="{{route('admin.user.update', $user->id)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="position-relative form-group">
                    <label for="name" class="">Name</label>
                    <input name="name" id="name" value="{{$user->name}}" placeholder="" type="text" class="form-control">
                </div>
                <div class="position-relative form-group">
                    <label for="email" class="">Email</label>
                    <input name="email" id="email" value="{{$user->email}}" placeholder="" type="email" class="form-control">
                </div>
                <div class="position-relative form-group">
                    <label for="phone" class="">Phone</label>
                    <input name="phone" id="phone" value="{{$user->phone}}" placeholder="" type="number" class="form-control">
                </div>
                <div class="position-relative form-group">
                    <label for="password" class="">Password</label>
                    <input name="password" id="password" placeholder="" type="password" class="form-control">
                </div>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-secondary mr-3 back_btn">Cancel</button>
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')

{!! JsValidator::formRequest('App\Http\Requests\UpdateUser') !!}

    <script type="text/javascript">
        $(document).ready(function() {
            
        });
    </script>
@endsection