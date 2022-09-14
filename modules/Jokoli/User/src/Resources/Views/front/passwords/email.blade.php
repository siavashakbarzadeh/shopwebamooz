@extends('User::front.master')
@section('title') بازیابی کلمه عبور | وب آموز @endsection

@section('content')
    <form action="{{ route('password.sendVerifyCodeEmail') }}" class="form" method="get">
        <a class="account-logo" href="/">
            <img src="{{ asset('img/weblogo.png') }}" alt="">
        </a>
        <div class="form-content form-account width-100">
            @if (session('status'))
                <div class="alert alert-success mb-15" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <label class="d-block width-100 mb-15">
                <input type="text" class="txt txt-l mb-0 @error('email') is-invalid @enderror" name="email"
                       value="{{ old('email') }}" placeholder="ایمیل" required autofocus autocomplete="email">
                @error('email')
                <div class="invalid-feedback mt-4">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
            </label>
            <button class="btn btn-recoverpass">بازیابی</button>
        </div>
        <div class="form-footer">
            <a href="{{ route('login') }}">صفحه ورود</a>
        </div>
    </form>
@endsection
