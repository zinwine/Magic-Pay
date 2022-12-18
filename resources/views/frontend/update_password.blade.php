@extends('frontend.layouts.app')
@section('page-name', 'Update Password')
@section('content')
<div class="update-password">
    <div class="card mb-3">
        <div class="img text-center">
            <img src="{{asset('img/password.png')}}" alt="">
        </div>
        <div class="card-body">
            <form action="{{route('update-password.store')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Old Password</label>
                    <input class="form-control @error('old_password') is-invalid @enderror" value="{{old('old_password')}}" type="password" name="old_password">
                    @error('old_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input class="form-control @error('new_password') is-invalid @enderror" value="{{old('new_password')}}" type="password" name="new_password">
                    @error('new_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-block btn_theme mt-4">Update Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
