@extends('Dashboard::master')
@section('title') ایجاد جلسه | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="مدیریت دوره‌ها">مدیریت دوره‌ها</a></li>
    <li><a href="{{ route('courses.details',$course->id) }}" title="جزئیات دوره‌">جزئیات دوره‌</a></li>
    <li><a title="ایجاد جلسه">ایجاد جلسه</a></li>
@endsection

@section('content')
    <div class="main-content padding-0">
        <p class="box__title">ایجاد جلسه جدید</p>
        <div class="row no-gutters bg-white">
            <div class="col-12">
                <form action="{{ route('lessons.store',$course->id) }}" method="post" class="padding-30"
                      enctype="multipart/form-data">
                    @csrf
                    <x-input type="text" name="title" placeholder="عنوان جلسه" required/>
                    <x-input type="text" name="slug" class="text-left" placeholder="نام انگلیسی جلسه"/>
                    @if(count($seasons))
                        <x-select name="season_id">
                            <option value="">انتخاب سرفصل</option>
                            @foreach($seasons as $season)
                                <option value="{{ $season->id }}"
                                        @if($season->id == old('season_id')) selected @endif>{{ $season->title }}</option>
                            @endforeach
                        </x-select>
                    @endif
                    <div class="d-flex multi-text">
                        <div class="mlg-15 flex-grow-1">
                            <x-input type="text" name="priority" class="text-left" placeholder="ردیف جلسه"/>
                        </div>
                        <div class="flex-grow-1">
                            <x-input type="text" name="duration" class="text-left"
                                     placeholder="مدت زمان دوره (برحسب دقیقه)" required/>
                        </div>
                    </div>
                    <div class="d-block margin-bottom-10">
                        <p class="box__title">ایا این درس رایگان است ؟ </p>
                        <div class="w-50">
                            <div class="notificationGroup">
                                <input id="lesson-upload-field-1" name="is_free" value="" type="radio" checked/>
                                <label for="lesson-upload-field-1">خیر</label>
                            </div>
                            <div class="notificationGroup mb-0">
                                <input id="lesson-upload-field-2" name="is_free" type="radio"/>
                                <label for="lesson-upload-field-2">بله</label>
                            </div>
                        </div>
                    </div>
                    <x-file name="attachment" title="آپلود جلسه" required/>
                    <x-textarea name="body" placeholder="توضیحات جلسه"></x-textarea>
                    <button class="btn btn-webamooz_net">ایجاد جلسه</button>
                </form>
            </div>
        </div>
    </div>
@endsection
