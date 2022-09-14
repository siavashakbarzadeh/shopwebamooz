@extends('Dashboard::master')
@section('title') بروزرسانی دسته بندی | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('categories.index') }}" title="مدیریت دسته‌بندی‌ها">مدیریت دسته‌بندی‌ها</a></li>
    <li><a title="بروزرسانی دسته بندی">بروزرسانی دسته بندی</a></li>
@endsection

@section('content')
    <div class="main-content padding-0 categories">
        <div class="row no-gutters">
            <div class="col-6 bg-white">
                <p class="box__title">بروزرسانی دسته بندی</p>
                <form action="{{ route('categories.update',$category->id) }}" method="post" class="padding-30">
                    @csrf
                    @method('patch')
                    <input type="text" name="title" value="{{ $category->title }}" required placeholder="نام دسته بندی" class="text">
                    <input type="text" name="slug" value="{{ $category->slug }}" required placeholder="نام انگلیسی دسته بندی" class="text">
                    <p class="box__title margin-bottom-15">انتخاب دسته پدر</p>
                    <select name="parent_id" id="parent_id">
                        <option value="">ندارد</option>
                        @foreach($categories as $categoryItem)
                            <option value="{{ $categoryItem->id }}" @if($categoryItem->id == $category->parent_id) selected @endif>{{ $categoryItem->title }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-webamooz_net">بروزرسانی</button>
                </form>
            </div>
        </div>
    </div>
@endsection
