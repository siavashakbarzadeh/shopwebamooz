<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@section('title') وب آموز @show</title>
    <link rel="stylesheet" href="{{ asset('panel/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('panel/css/responsive_991.css') }}" media="(max-width:991px)">
    <link rel="stylesheet" href="{{ asset('panel/css/responsive_768.css') }}" media="(max-width:768px)">
    <link rel="stylesheet" href="{{ asset('panel/css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('panel/css/jquery.toast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @yield('head')
</head>
