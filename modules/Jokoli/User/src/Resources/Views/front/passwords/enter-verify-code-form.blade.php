@extends('User::front.master')
@section('title') فعالسازی حساب کاربری | وب آموز @endsection

@section('content')
    <div class="form">
        <a class="account-logo" href="/">
            <img src="{{ asset('img/weblogo.png') }}" alt="">
        </a>
        <div class="card-header">
            <p class="activation-code-title">کد فرستاده شده به ایمیل <span>{{ request()->email }}</span>
                را وارد کنید . ممکن است ایمیل به پوشه spam فرستاده شده باشد
            </p>
        </div>
        <div class="form-content form-content1">
            <form action="{{ route('password.check-verify-code') }}" method="post" class="width-100">
                @csrf
                <input type="hidden" name="email" value="{{ request()->email }}">
                <label class="d-block width-100 mb-15">
                    <input name="verify_code" class="activation-code-input mb-0" required placeholder="فعال سازی">
                    @error('verify_code')
                    <div class="invalid-feedback mt-4">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                </label>
                <button class="btn i-t">تایید</button>
            </form>
            <form action="{{ route('verification.resend') }}" id="resend_form" method="post" class="d-flex justify-content-center">
                @csrf
                <a href="{{ route('verification.resend') }}" onclick="event.preventDefault();document.getElementById('resend_form').submit();" class="font-size-13">ارسال مجدد کد فعالسازی</a>
            </form>
        </div>
        <div class="form-footer">
            <form action="{{ route('logout') }}" method="post" id="logout">
                @csrf
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();document.getElementById('logout').submit();">صفحه ثبت نام</a>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('js/activation-code.js') }}"></script>
@endsection
