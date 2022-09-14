@extends('Dashboard::master')
@section('title') بروزرسانی کاربر | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('users.index') }}" title="مدیریت کاربران">مدیریت کاربران</a></li>
    <li><a title="بروزرسانی کاربر">بروزرسانی کاربر</a></li>
@endsection

@section('content')
    <div class="main-content font-size-13">
        <div class="row no-gutters bg-white margin-bottom-20">
            <div class="col-12">
                <p class="box__title">بروزرسانی کاربر</p>
                <form action="{{ route('users.update',$user->id) }}" class="padding-30" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <x-input type="text" name="name" value="{{ $user->name }}" placeholder="نام و نام خانوادگی"
                             required/>
                    <x-input type="email" name="email" value="{{ $user->email }}" class="text-left" placeholder="ایمیل"
                             required/>
                    <x-input type="text" name="mobile" value="{{ $user->mobile }}" class="text-left"
                             placeholder="موبایل"/>
                    <x-input type="text" name="username" value="{{ $user->username }}" class="text-left"
                             placeholder="نام کاربری"/>
                    <x-input type="text" name="headline" value="{{ $user->headline }}" placeholder="عنوان کاربر"/>
                    <x-input type="text" name="telegram" value="{{ $user->telegram }}" class="text-left"
                             placeholder="تلگرام"/>
                    <x-input type="password" name="password" class="text-left" placeholder="کلمه عبور جدید"
                             autocomplete="off"/>
                    <x-select name="status" required>
                        <option value="">وضعیت کاربر</option>
                        @foreach(\Jokoli\User\Enums\UserStatus::asSelectArray() as $key=>$status)
                            <option value="{{ $key }}"
                                    @if((old('status') && $key == old('status')) || $key == $user->status) selected @endif>{{ $status }}</option>
                        @endforeach
                    </x-select>
                    <x-select name="role">
                        <option value="">فاقد نقش کاربری</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}"
                                    @if((old('role') && $role->name == old('role')) || $user->hasRole($role->name)) selected @endif>{{ $role->name_fa }}</option>
                        @endforeach
                    </x-select>
                    <x-file name="image" :value="$user->image" title="تصویر کاربر"/>
                    <x-textarea name="bio" placeholder="بیو">{{ $user->bio }}</x-textarea>
                    <button class="btn btn-webamooz_net">بروزرسانی کاربر</button>
                </form>
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
                                <td><a href="{{ $course->teacher->profilePath() }}">{{ $course->teacher->name }}</a></td>
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
    </div>
@endsection
