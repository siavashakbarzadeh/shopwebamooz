@extends('User::front.master')
@section('title') تایید کد فعالسازی | وب آموز @endsection

@section('content')
    <form action="" class="form" method="post">
        @csrf
        <a class="account-logo" href="/">
            <img src="{{ asset('img/weblogo.png') }}" alt="">
        </a>
        <div class="card-header">
            <p class="activation-code-title">کد فرستاده شده به ایمیل  <span>Mohammadniko3@gmail.com</span>
                را وارد کنید . ممکن است ایمیل به پوشه spam فرستاده شده باشد
            </p>
        </div>
        <div class="form-content form-content1">
            <input class="activation-code-input" placeholder="فعال سازی">
            <br>
            <button class="btn i-t">تایید</button>

        </div>
        <div class="form-footer">
            <a href="{{ route('register') }}">صفحه ثبت نام</a>
        </div>
    </form>
@endsection
@section('foot')
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('js/activation-code.js') }}"></script>
@endsection
