@extends('User::front.master')
@section('title') ثبت نام | وب آموز @endsection

@section('content')
    <form action="{{ route('register') }}" class="form" method="post">
        @csrf
        <a class="account-logo" href="/">
            <img src="{{ asset('img/weblogo.png') }}" alt="">
        </a>
        <div class="form-content form-account">
            <label class="d-block width-100 mb-15">
                <input type="text" class="txt mb-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"
                       placeholder="نام و نام خانوادگی *"
                       required autofocus autocomplete="name">
                @error('name')
                <div class="invalid-feedback mt-4">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </label>
            <label class="d-block width-100 mb-15">
                <input type="email" class="txt txt-l mb-0 @error('email') is-invalid @enderror" name="email"
                       value="{{ old('email') }}" placeholder="ایمیل *" required autocomplete="email">
                @error('email')
                <div class="invalid-feedback mt-4">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </label>
            <label class="d-block width-100 mb-15">
                <input type="text" class="txt txt-l mb-0 @error('mobile') is-invalid @enderror" name="mobile"
                       value="{{ old('mobile') }}" placeholder="شماره موبایل" autocomplete="mobile">
                @error('mobile')
                <div class="invalid-feedback mt-4">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </label>
            <label class="d-block width-100 mb-15">
                <input type="password" class="txt txt-l mb-0 @error('password') is-invalid @enderror" name="password" placeholder="کلمه عبور *" required autocomplete="new-password">
            </label>
            <label class="d-block width-100 mb-15">
                <input type="password" class="txt txt-l mb-0 @error('password') is-invalid @enderror" name="password_confirmation" placeholder="تایید کلمه عبور *" required autocomplete="new-password">
                @error('password')
                <div class="invalid-feedback mt-4">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </label>
            <span class="rules">کلمه عبور باید حداقل ۶ کاراکتر و ترکیبی از حروف بزرگ، حروف کوچک، اعداد و کاراکترهای غیر الفبا مانند !@#$%^&*() باشد.</span>
            <br>
            <button class="btn continue-btn">ثبت نام و ادامه</button>
        </div>
        <div class="form-footer">
            <a href="{{ route('login') }}">صفحه ورود</a>
        </div>
    </form>
@endsection
