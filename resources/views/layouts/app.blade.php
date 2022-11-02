<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ URL::asset(config('logo.logos')::first()->icon)}}">
    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <link rel="stylesheet" href="{{asset('login_css/fonts/icomoon/style.css')}}">
    <link rel="stylesheet" href="{{asset('login_css/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('login_css/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('login_css/css/style.css')}}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css"> --}}
    <style>
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{ asset('login_css/images/loader.gif')}}") 50% 50% no-repeat white ;
            opacity: .8;
            background-size:120px 120px;
        }
    </style>
    <!-- LogIN CSS -->
  

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
</head>
<body>
    <div id = "loader" style="display:none;" class="loader">
    </div>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="{{asset('login_css/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('login_css/js/popper.min.jss')}}"></script>
    <script src="{{asset('login_css/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('login_css/js/main.js')}}"></script>
    <script type='text/javascript'>
        function show() {
            document.getElementById("loader").style.display="block";
        }
    </script>
</body>
</html>
