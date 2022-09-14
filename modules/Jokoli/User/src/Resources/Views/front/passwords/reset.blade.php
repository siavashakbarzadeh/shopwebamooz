@extends('User::front.master')
@section('title') تغییر کلمه عبور | وب آموز @endsection

@section('content')
    <div class="form">
        @csrf
        <a class="account-logo" href="/">
            <img src="{{ asset('img/weblogo.png') }}" alt="">
        </a>
        <div class="form-content form-account width-100">
            <form action="{{ route('password.update') }}" method="post">
                @csrf
                <label class="d-block width-100 mb-15">
                    <input type="password" class="txt txt-l mb-0 @error('password') is-invalid @enderror"
                           name="password" placeholder="کلمه عبور جدید" required autocomplete="new-password">
                    @error('password')
                    <div class="invalid-feedback mt-4">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                </label>
                <label class="d-block width-100 mb-15">
                    <input type="password" class="txt txt-l mb-0" name="password_confirmation"
                           placeholder="تایید کلمه عبور جدید" required autocomplete="new-password">
                </label>
                <span class="rules">کلمه عبور باید حداقل ۶ کاراکتر و ترکیبی از حروف بزرگ، حروف کوچک، اعداد و کاراکترهای غیر الفبا مانند !@#$%^&*() باشد.</span>
                <button class="btn btn-recoverpass">تغییر کلمه عبور</button>
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
