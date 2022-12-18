<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="csrf-token" content="{{csrf_token()}}">

    <title>@yield('title')</title>

    <link href="{{asset('backend/css/main.css')}}" rel="stylesheet"></head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet"></head>
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css" rel="stylesheet"></head>
    <link rel="stylesheet" href="{{asset('backend/css/style.css')}}">

    @yield('styles')

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        
        {{-- ****************** Header Section ***************************** --}}
        @include('backend.layouts.header')
        
        <div class="app-main">

                {{-- *********************** Sider Section ****************** --}}
                @include('backend.layouts.sidebar')    
                
                <div class="app-main__outer">
                    <div class="app-main__inner">   
                        
                        {{-- **************************** Main Content *********************** --}}
                        @yield('content')
                        
                    </div>
                    
                    {{-- ***************** Footer Section ************************ --}}
                    @include('backend.layouts.footer')    
                    
                </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('backend/js/main.js')}}"></script></body>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script></body>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

    {{-- *********************** Jsvalidation ******************* --}}
    <script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    {{-- *********************** Sweetalert2 ******************* --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function(){
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if(token){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF_TOKEN' : token.content
                    }
                })
            }
            $('.back_btn').on('click', function(){
                window.history.go(-1);
                return false;
            });

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

    });
    </script>

    @yield('scripts')

</body>
</html>
