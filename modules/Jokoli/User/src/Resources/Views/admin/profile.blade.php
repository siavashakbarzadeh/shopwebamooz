@extends('Dashboard::master')
@section('title') اطلاعات کاربری | @parent @endsection
@section('breadcrumb')
    <li><a title="اطلاعات کاربری">اطلاعات کاربری</a></li>
@endsection

@section('content')
    <div class="main-content">
        <div class="user-info bg-white padding-30 font-size-13">
            <div class="d-block">
                <x-user-user-photo/>
                <form action="{{ route('profile') }}" method="post">
                    @csrf
                    @method('patch')
                    <x-input type="text" name="name" value="{{ auth()->user()->name }}" placeholder="نام و نام خانوادگی"
                             required/>
                    <x-input type="email" name="email" value="{{ auth()->user()->email }}" class="text-left"
                             placeholder="ایمیل"
                             required/>
                    <x-input type="text" name="mobile" value="{{ auth()->user()->mobile }}" class="text-left"
                             placeholder="موبایل"/>
                    @can(\Jokoli\Permission\Enums\Permissions::Teach)
                        <x-input type="text" name="headline" value="{{ auth()->user()->headline }}"
                                 placeholder="عنوان کاربر"/>
                        <x-input type="text" name="card_number" value="{{ auth()->user()->card_number }}"
                                 class="text-left"
                                 placeholder="شماره کارت بانکی"/>
                        <x-input type="text" name="iban" value="{{ auth()->user()->iban }}" class="text-left"
                                 placeholder="شماره شبا بانکی"/>
                        <x-input type="text" name="username" value="{{ auth()->user()->username }}" class="text-left"
                                 placeholder="نام کاربری">
                            @if(auth()->user()->username)
                                <p class="input-help text-left" dir="ltr">
                                    <a href="{{ auth()->user()->profilePath() }}">{{ auth()->user()->profilePath() }}</a>
                                </p>
                            @endif
                        </x-input>
                    @endcan
                    <x-input type="password" name="password" class="text-left" placeholder="کلمه عبور جدید"
                             autocomplete="off">
                        <p class="rules">رمز عبور باید حداقل ۶ کاراکتر و ترکیبی از حروف بزرگ، حروف کوچک، اعداد و
                            کاراکترهای
                            غیر الفبا مانند <strong>!@#$%^&*()</strong> باشد.</p>
                    </x-input>
                    @can(\Jokoli\Permission\Enums\Permissions::Teach)
                        <x-textarea name="bio" placeholder="درباره من">{{ auth()->user()->bio }}</x-textarea>
                    @endcan
                    <button class="btn btn-webamooz_net">ذخیره تغییرات</button>
                </form>
            </div>
        </div>

    </div>
@endsection
