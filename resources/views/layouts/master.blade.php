<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title> @yield('title') - {{ config('app.name') }} </title>
  <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

  <!-- Embed CSS -->
  <link rel="stylesheet" href="{{ asset('common/css/normalize.min.css') }}">
  <link rel="stylesheet" href="{{ asset('common/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('common/css/bootstrap-theme.min.css') }}">
  <link rel="stylesheet" href="{{ asset('common/css/animate.css') }}">
  <link rel="stylesheet" href="{{ asset('common/css/fontawesome/css/all.css') }}">
  <link rel="stylesheet" href="{{ asset('common/css/owl.carousel.min.css') }}">
  <link rel="stylesheet" href="{{ asset('common/css/owl.theme.default.min.css') }}">
  <link rel="stylesheet" href="{{ asset('common/css/sweetalert2.min.css') }}">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  @if(Request::route()->getName() != 'show_cart')
  <link rel="stylesheet" href="{{ asset('css/minicart.css') }}">
  @endif
  @yield('css')
</head>
<body>
   
    <!-- Header -->
    @include('layouts.header')

    <div class="container">
      <!-- Site Content -->
      <div class="site-content">
        @yield('content')
      </div>
    </div>

    @if(Request::route()->getName() != 'show_cart')
    <!-- MiniCart display -->
    @include('layouts.minicart')
    @endif

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Embed Scripts -->
    <script src="{{ asset('common/js/jquery-3.3.1.js') }}"></script>
    <script src="{{ asset('common/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('common/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('common/js/sweetalert2.min.js') }}"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('js/custom.js') }}"></script>
    @if(Request::route()->getName() != 'show_cart')
    <script src="{{ asset('js/minicart.js') }}"></script>
    @endif
    @yield('js')
</body>
</html>
