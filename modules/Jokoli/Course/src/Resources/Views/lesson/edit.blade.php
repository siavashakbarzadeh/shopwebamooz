@extends('Dashboard::master')
@section('title') بروزرسانی جلسه | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="مدیریت دوره‌ها">مدیریت دوره‌ها</a></li>
    <li><a href="{{ route('courses.details',$lesson->course->id) }}" title="جزئیات دوره‌">جزئیات دوره‌</a></li>
    <li><a title="بروزرسانی جلسه">بروزرسانی جلسه</a></li>
@endsection

@section('content')
    <div class="main-content padding-0">
        <p class="box__title">بروزرسانی جلسه</p>
        <div class="row no-gutters bg-white">
            <div class="col-12">
                <form action="{{ route('lessons.update',$lesson->id) }}" method="post" class="padding-30"
                      enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <x-input type="text" name="title" value="{{ $lesson->title }}" placeholder="عنوان جلسه" required/>
                    <x-input type="text" name="slug" value="{{ $lesson->slug }}" class="text-left" placeholder="نام انگلیسی جلسه"/>
                    @if(count($seasons))
                        <x-select name="season_id">
                            <option value="">انتخاب سرفصل</option>
                            @foreach($seasons as $season)
                                <option value="{{ $season->id }}"
                                        @if(($season->id == old('season_id')) || $season->id == $lesson->season_id) selected @endif>{{ $season->title }}</option>
                            @endforeach
                        </x-select>
                    @endif
                    <div class="d-flex multi-text">
                        <div class="mlg-15 flex-grow-1">
                            <x-input type="text" name="priority" value="{{ $lesson->priority }}" class="text-left" placeholder="ردیف جلسه"/>
                        </div>
                        <div class="flex-grow-1">
                            <x-input type="text" name="duration" class="text-left" value="{{ $lesson->duration }}"
                                     placeholder="مدت زمان دوره (برحسب دقیقه)" required/>
                        </div>
                    </div>
                    <div class="d-block margin-bottom-10">
                        <p class="box__title">ایا این درس رایگان است ؟ </p>
                        <div class="w-50">
                            <div class="notificationGroup">
                                <input id="lesson-upload-field-1" name="is_free" value="" type="radio" @if(!$lesson->is_free) checked @endif/>
                                <label for="lesson-upload-field-1">خیر</label>
                            </div>
                            <div class="notificationGroup mb-0">
                                <input id="lesson-upload-field-2" name="is_free" type="radio" @if($lesson->is_free) checked @endif/>
                                <label for="lesson-upload-field-2">بله</label>
                            </div>
                        </div>
                    </div>
                    <x-file name="attachment" title="آپلود جلسه" :value="$lesson->media" required/>
                    <x-textarea name="body" placeholder="توضیحات جلسه">{{ $lesson->body }}</x-textarea>
                    <button class="btn btn-webamooz_net">بروزرسانی جلسه</button>
                </form>
            </div>
        </div>
    </div>
@endsection
