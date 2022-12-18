<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    {{-- *********************** Bootstrap 4 CSS ****************** --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    {{-- *********************** Font Awesome CSS ****************** --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" >

    {{-- *********************** DatePicker Styles ****************** --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


    {{-- *********************** Extra Styles ****************** --}}

    <link rel="stylesheet" href="{{asset('frontend/css/style.css')}}">

    @yield('style')
</head>
<body>
    <div id="app">
        {{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> --}}

        <div class="header-menu">
            <div class="d-flex justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-2 text-center">
                            @if (!request()->is('/'))
                            <a href="#" class="back_btn"><i class="fas fa-angle-left"></i></a>
                            @endif
                        </div>
                        <div class="col-8 text-center"><h3>@yield('page-name')</h3></div>
                        <div class="col-2 text-center"><a href="{{url('notification')}}"><i class="fas fa-bell"></i><span class="badge badge-pill badge-danger unread_bell">@if($unread_noti_count > 0) {{$unread_noti_count}} @endif</span></a></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="content">
            <div class="d-flex justify-content-center">
                <div class="col-md-8">
                    @yield('content')
                </div>
            </div>
        </div>
        
        <div class="bottom-menu">
            <a href="{{url('scan_and_pay')}}" class="scan-tab">
                <div class="inside">
                    <i class="fas fa-qrcode"></i>
                </div>
            </a>
            <div class="d-flex justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-3 text-center"><a href="{{route('home')}}"><i class="fas fa-home"><p class="">Home</p></i></a></div>
                        <div class="col-3 text-center"><a href="{{route('wallet')}}"><i class="fas fa-wallet"><p class="">Wallet</p></i></a></div>
                        <div class="col-3 text-center"><a href="{{route('transation')}}"><i class="fas fa-exchange-alt"><p class="">Transation</p></i></a></div>
                        <div class="col-3 text-center"><a href="{{route('profile')}}"><i class="fas fa-user"><p class="">Profile</p></i></a></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- *********************** Bootstrap 4 Scripts ****************** --}}
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    {{-- *********************** Sweetalert2 ******************* --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- *********************** Jscroll Js ******************* --}}
    <script src="{{asset('frontend/js/jscroll.min.js')}}"></script>

    {{-- *********************** Scan QR Js ******************* --}}


    <script>
        $(document).ready(function(){
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if(token){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF_TOKEN' : token.content,
                        'Content-Type' : 'application/json',
                        'accept' : 'application/json'
                    }
                })
            }


            const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
            })

            @if(session('create'))
                Toast.fire({
                icon: 'success',
                title: "{{session('create')}}"
                });
            @endif

            @if(session('update'))
                Toast.fire({
                icon: 'success',
                title: "{{session('update')}}"
                });
            @endif


            $('.back_btn').on('click', function(e){
                e.preventDefault();
                window.history.go(-1);
                return false;
            });

        });
    </script>

    {{-- *********************** Extra Scripts ****************** --}}
   @yield('script')
</body>
</html>
    