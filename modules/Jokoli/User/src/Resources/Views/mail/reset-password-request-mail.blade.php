@component('mail::message')
# بازیابی کلمه عبور حساب کاربری شما در {{ config('app.name') }}

این کد برای بازیابی حساب کاربری شما در وبسایت {{ config('app.name') }} ارسال شده است، اگر درخواست کننده شما نیستید، این ایمیل را نادیده بگیرید.

@component('mail::panel')
کد بازیابی: {{ $code }}
@endcomponent

با تشکر,<br>
{{ config('app.name') }}
@endcomponent
