@extends('Dashboard::master')
@section('title') بروزرسانی نقش‌ کاربری | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('permissions.index') }}" title="مدیریت نقش‌های کاربری">مدیریت نقش‌های کاربری</a></li>
    <li><a title="بروزرسانی نقش‌ کاربری">بروزرسانی نقش‌ کاربری</a></li>
@endsection

@section('content')
    <div class="main-content padding-0 categories">
        <div class="row no-gutters  ">
            <div class="col-6 bg-white h-100">
                <p class="box__title">بروزرسانی نقش‌ کاربری</p>
                <form action="{{ route('permissions.update',$role->id) }}" method="post" class="padding-30">
                    @csrf
                    @method('patch')
                    <label class="d-block width-100 mb-15">
                        <input type="text" class="text mb-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $role->name }}"
                               placeholder="نام نقش کاربری"
                               required autofocus autocomplete="name">
                        @error('name')
                        <div class="invalid-feedback mt-4">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </label>
                    <p class="box__title margin-bottom-15">انتخاب مجوزها</p>
                    <div class="mb-15">
                        @foreach($permissions as $permission)
                            <label class="ui-checkbox margin-bottom-5">
                                <input type="checkbox" name="permissions[]" @if($role->permissions->contains($permission->id)) checked @endif value="{{ $permission->id }}">
                                <span class="checkmark position-relative margin-left-10"></span>
                                {{ $permission->name_fa }}
                            </label>
                        @endforeach
                        @error('permissions')
                        <div class="invalid-feedback mt-4">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <button class="btn btn-webamooz_net">بروزرسانی</button>
                </form>
            </div>
        </div>
    </div>
@endsection
