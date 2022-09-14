@extends('Dashboard::master')
@section('title') اطلاعات کاربری | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('users.index') }}" title="مدیریت کاربران">مدیریت کاربران</a></li>
    <li><a title="اطلاعات کاربری">اطلاعات کاربری</a></li>
@endsection

@section('content')
    <div class="main-content font-size-13">
        <div class="row no-gutters bg-white margin-bottom-20">
            <div class="col-12">
                <p class="box__title">اطلاعات کاربری {{ $user->name }}</p>
                <div class="padding-30">
                    <div class="d-flex">
                        <div class="mlg-15">
                            <img src="{{ $user->thumb }}" class="border-radius-circle" width="80"
                                 alt="{{ $user->name }}">
                        </div>
                        <div class="flex-grow-1">
                            <div class="">نام و نام خانوادگی: {{ $user->name }}</div>
                            <div class="">ایمیل: {{ $user->email }}</div>
                            <div class="">شماره موبایل: {{ $user->mobile }}</div>
                            <div class="">عنوان: {{ $user->headline }}</div>
                            @if($user->roles->first())
                                <div class="">سطح کاربر: {{ optional($user->roles->first())->name_fa }}</div>
                            @endif
                            <div class="">تعداد دوره های شرکت کرده: {{ count($user->purchases)." دوره" }}</div>
                            @if($user->hasPermissionTo(\Jokoli\Permission\Enums\Permissions::Teach))
                                <div class="">تعداد دوره: {{ count($user->courses)." دوره" }}</div>
                            @endif
                            <div class="">تاریخ ثبت نام: {{ verta($user->created_at)->formatJalaliDatetime() }}</div>
                            <div class="">تاریخ تایید
                                حساب: {{ verta($user->verified_at)->formatJalaliDatetime() }}</div>
                            <div class="">بیو: {{ $user->bio }}</div>
                        </div>
                        <div class="">
                            <div class="d-flex align-items-center">
                                <div class="font-size-12">موجودی:</div>
                                <div class="d-flex align-items-center text-dark mr-4">
                                    <span class="font-size-15">{{ number_format($user->balance) }}</span>
                                    <span class="font-size-12 mr-2">تومان</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row no-gutters">
            <div
                class="{{ $user->hasPermissionTo(\Jokoli\Permission\Enums\Permissions::Teach) ? 'col-6' : 'col-12' }} margin-left-10 margin-bottom-20">
                <p class="box__title">درحال یادگیری</p>
                <div class="table__box">
                    <table class="table">
                        <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>شناسه</th>
                            <th>نام دوره</th>
                            <th>نام مدرس</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->purchases as $course)
                            <tr role="row" class="">
                                <td>{{ $course->id }}</td>
                                <td><a href="{{ $course->path() }}">{{ $course->title }}</a></td>
                                <td><a href="{{ $course->teacher->profilePath() }}">{{ $course->teacher->name }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($user->hasPermissionTo(\Jokoli\Permission\Enums\Permissions::Teach))
                <div class="col-6 margin-bottom-20">
                    <p class="box__title">دوره های مدرس {{ $user->name }}</p>
                    <div class="table__box">
                        <table class="table">
                            <thead role="rowgroup">
                            <tr role="row" class="title-row">
                                <th>شناسه</th>
                                <th>نام دوره</th>
                                <th class="white-space-nowrap">شرکت کنندگان</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->courses as $course)
                                <tr role="row" class="">
                                    <td>{{ $course->id }}</td>
                                    <td><a href="">{{ $course->title }}</a></td>
                                    <td>{{ count($course->students) }} نفر</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
        <div class="row no-gutters">
            <div
                class="col-6 margin-left-10 margin-bottom-20">
                <p class="box__title">پرداخت ها</p>
                <div class="table__box">
                    <table class="table">
                        <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>شناسه</th>
                            <th>محصول</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                            <th>تاریخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->payments as $payment)
                            <tr role="row" class="">
                                <td>{{ $payment->id }}</td>
                                <td>
                                    <a href="{{ $payment->paymentable->path() }}">{{ $payment->paymentable->title }}</a>
                                </td>
                                <td>{{ number_format($payment->amount)." تومان" }}</td>
                                <td class="{{ $payment->getStatusCssClass() }}">{{ $payment->status_fa }}</td>
                                <td>{{ verta($payment->created_at)->formatJalaliDatetime() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-6 margin-bottom-20">
                <p class="box__title">درخواست های تسویه</p>
                <div class="table__box">
                    <table class="table">
                        <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>شناسه</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                            <th>تاریخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->settlements as $settlement)
                            <tr role="row" class="">
                                <td>{{ $settlement->id }}</td>
                                <td>{{ number_format($settlement->amount)." تومان" }}</td>
                                <td class="{{ $settlement->getStatusCssClass() }}">{{ $settlement->status_fa }}</td>
                                <td>{{ verta($settlement->created_at)->formatJalaliDatetime() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
