@extends('Dashboard::master')
@section('title') مدیریت نقش‌های کاربری | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('permissions.index') }}" title="مدیریت نقش‌های کاربری">مدیریت نقش‌های کاربری</a></li>
@endsection

@section('content')
    <div class="main-content padding-0 categories">
        <div class="row no-gutters">
            <div class="col-8 margin-left-10 margin-bottom-15 border-radius-3">
                <p class="box__title">نقش‌های کاربری</p>
                <div class="table__box">
                    <table class="table">
                        <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>شناسه</th>
                            <th>نقش کاربری</th>
                            <th>مجوزها</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr role="row" class="">
                                <td><a href="">{{ $role->id }}</a></td>
                                <td><a href="">{{ $role->name_fa }}</a></td>
                                <td>
                                    @if($role->permissions->count())
                                    <ul>
                                        @foreach($role->permissions as $permission)
                                        <li>{{ $permission->name_fa }}</li>
                                        @endforeach
                                    </ul>
                                    @else
                                        ندارد
                                    @endif
                                </td>
                                <td>
                                    <a href=""
                                       onclick="deleteItem(event,'{{ route('permissions.destroy',$role->id) }}')"
                                       class="item-delete mlg-15" title="حذف"></a>
                                    <a href="{{ route('permissions.edit',$role->id) }}" class="item-edit "
                                       title="ویرایش"></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-4 bg-white h-100">
                @include('Permission::create')
            </div>
        </div>
    </div>
@endsection
