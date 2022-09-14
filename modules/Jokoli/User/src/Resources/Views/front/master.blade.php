<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css?v='.uniqid()) }}">
    <title>@section('title') وب آموز | آموزش برنامه‌ نویسی و طراحی وب @show</title>
</head>
<body>
<main>
    <div class="account">
        @yield('content')
    </div>
</main>
</body>
@yield('js')
</html>
