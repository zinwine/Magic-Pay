@extends('frontend.layouts.app_plain')
@section('title', 'Login')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-3">
            <div class="card auth-form">
                <div class="card-body">
                    <h3 class="text-center py-3">Login</h3>
                    <p class="text-center text-muted">Please! Fill the form to Login</p>
                    <form method="POST" action="{{ route('login') }}" autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="">{{ __('E-Mail') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                        </div>

                        <button type="submit" class="btn btn_theme text-white btn-block my-3">Login</button>
                        <div class="d-flex justify-content-between">
                            <a href="{{route('register')}}">Register Now</a>
                            @if (Route::has('password.request'))
                                <a class="" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
