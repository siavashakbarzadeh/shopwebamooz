@extends('Dashboard::master')
@section('title') مدیریت کاربران | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('users.index') }}" title="مدیریت کاربران">مدیریت کاربران</a></li>
@endsection

@section('content')
    <div class="main-content font-size-13">
        <div class="tab__box">
            <div class="tab__items">
                <a class="tab__item is-active" href="users.html">همه کاربران</a>
                <a class="tab__item" href="">مدیران</a>
                <a class="tab__item" href="">مدرسین</a>
                <a class="tab__item" href="">نویسنده</a>
                <a class="tab__item" href="">کاربران تاییده نشده</a>
                <a class="tab__item" href="">کاربران تایید شده</a>
                <a class="tab__item" href="create-user.html">ایجاد کاربر جدید</a>
            </div>
        </div>
        <div class="d-flex flex-space-between item-center flex-wrap padding-30 border-radius-3 bg-white">
            <div class="t-header-search">
                <form action="" onclick="event.preventDefault();">
                    <div class="t-header-searchbox font-size-13">
                        <input type="text" class="text search-input__box font-size-13" placeholder="جستجوی کاربر">
                        <div class="t-header-search-content ">
                            <input type="text" class="text" placeholder="ایمیل">
                            <input type="text" class="text" placeholder="شماره">
                            <input type="text" class="text" placeholder="آی پی">
                            <input type="text" class="text margin-bottom-20" placeholder="نام و نام خانوادگی">
                            <btutton class="btn btn-webamooz_net">جستجو</btutton>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table__box">
            <table class="table">
                <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th>شناسه</th>
                    <th>نام و نام خانوادگی</th>
                    <th>ایمیل</th>
                    <th>شماره موبایل</th>
                    <th>سطح کاربری</th>
                    <th>تاریخ عضویت</th>
                    <th>ای پی</th>
                    <th>وضعیت حساب</th>
                    <th>درحال یادگیری</th>
                    <th>وضعیت تایید</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr role="row" class="">
                        <td>{{ $user->id }}</td>
                        <td><a href="{{ $user->infoPath() }}">{{ $user->name }}</a></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->mobile }}</td>
                        <td>{{ optional($user->roles->first())->name_fa }}</td>
                        <td>{{ verta($user->created_at)->formatJalaliDate() }}</td>
                        <td>{{ $user->ip }}</td>
                        <td class="{{ $user->getStatusCssClass() }}">{{ $user->status_fa }}</td>
                        <td>5 دوره</td>
                        <td class="js-verify-status {{ $user->hasVerifiedEmail() ? 'text-success' : 'text-error' }}">{{ trans($user->hasVerifiedEmail() ? 'User::user.approved' : 'User::user.not-approved') }}</td>
                        <td>
                            <a href=""
                               onclick="deleteItem(event,'{{ route('users.destroy',$user->id) }}')"
                               class="item-delete mlg-15" title="حذف"></a>
                            <a href="" onclick="updateConfirmationStatus(event,'{{ route('users.manual-verify',$user->id) }}','js-verify-status')" class="item-confirm mlg-15"
                               title="تایید"></a>
                            <a href="" class="item-reject mlg-15" title="رد"></a>
                            <a href="edit-user.html" target="_blank" class="item-eye mlg-15" title="مشاهده"></a>
                            <a href="{{ route('users.edit',$user->id) }}" class="item-edit " title="ویرایش"></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
