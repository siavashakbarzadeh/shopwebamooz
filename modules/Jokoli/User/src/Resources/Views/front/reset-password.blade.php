@extends('User::front.master')
@section('title') بازیابی کلمه عبور | وب آموز @endsection

@section('content')
    <form action="" class="form" method="post">
        @csrf
        <a class="account-logo" href="/">
            <img src="{{ asset('img/weblogo.png') }}" alt="">
        </a>
        <div class="form-content form-account">
            <input type="text" class="txt-l txt" placeholder="ایمیل">
            <br>
            <button class="btn btn-recoverpass">بازیابی</button>
        </div>
        <div class="form-footer">
            <a href="{{ route('register') }}">صفحه ورود</a>
        </div>
    </form>
@endsection
