@extends('Dashboard::master')
@section('title') ایجاد تیکت | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('tickets.index') }}" title="مدیریت تیکت ها">مدیریت تیکت ها</a></li>
    <li><a title="ایجاد تیکت">ایجاد تیکت</a></li>
@endsection

@section('content')
    <div class="main-content padding-0">
        <p class="box__title">ایجاد تیکت جدید</p>
        <div class="row no-gutters bg-white">
            <div class="col-12">
                <form action="{{ route('tickets.store') }}" method="post" class="padding-30" enctype="multipart/form-data">
                    @csrf
                    <x-input type="text" name="title" placeholder="عنوان تیکت"/>
                    <x-textarea name="body" placeholder="توضیحات تیکت"></x-textarea>
                    <x-file name="attachment" title="آپلود فایل پیوست"/>
                    <button class="btn btn-webamooz_net">ایجاد تیکت</button>
                </form>
            </div>
        </div>
    </div>
@endsection
