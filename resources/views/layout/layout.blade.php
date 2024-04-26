<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Cars')</title>

    <link href="{{ asset('assets/vendor/fonts-vendor.css') }}" rel="stylesheet"/>

    <link rel="stylesheet"
          href="{{ asset('assets/vendor/bootstrap-icons.css') }}"/>

    <link href="{{ asset('assets/vendor/bootstrap.min.css') }}" rel="stylesheet" crossorigin="anonymous">

    <link href="{{ asset('assets/vendor/toastr.min.css') }}" rel="stylesheet">


    <script src="{{ asset('assets/vendor/jquery-3.6.0.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body>
<main>
    @yield('content')
</main>
<script src="{{ asset('assets/vendor/bootstrap.bundle.min.js') }}"
        crossorigin="anonymous"></script>
<script src="{{ asset('assets/vendor/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
