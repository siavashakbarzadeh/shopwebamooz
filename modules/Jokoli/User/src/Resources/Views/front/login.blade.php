@extends('User::front.master')
@section('title') ورود به حساب کاربری | وب آموز @endsection

@section('content')
    <form action="{{ route('login') }}" class="form" method="post">
        @csrf
        <a class="account-logo" href="/">
            <img src="{{ asset('img/weblogo.png') }}" alt="">
        </a>
        <div class="form-content form-account width-100">
            <label class="d-block width-100 mb-15">
                <input type="text" class="txt txt-l mb-0 @error('email') is-invalid @enderror" name="email"
                       value="{{ old('email') }}" placeholder="ایمیل یا شماره موبایل" required autofocus autocomplete="email">
                @error('email')
                <div class="invalid-feedback mt-4">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </label>
            <label class="d-block width-100 mb-15">
                <input type="password" class="txt txt-l mb-0 @error('password') is-invalid @enderror" name="password" placeholder="کلمه عبور" required autocomplete="new-password">
                @error('password')
                <div class="invalid-feedback mt-4">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </label>
            <button class="btn btn--login">ورود</button>
            <label class="ui-checkbox">
                مرا بخاطر داشته باش
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <span class="checkmark"></span>
            </label>
            <div class="recover-password">
                <a href="{{ route('password.request') }}">بازیابی رمز عبور</a>
            </div>
        </div>
        <div class="form-footer">
            <a href="{{ route('register') }}">صفحه ثبت نام</a>
        </div>
    </form>
@endsection
