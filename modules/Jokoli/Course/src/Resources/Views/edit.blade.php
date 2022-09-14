@extends('Dashboard::master')
@section('title') مدیریت دوره‌ها | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="مدیریت دوره‌ها">مدیریت دوره‌ها</a></li>
    <li><a title="بروزرسانی دوره‌">بروزرسانی دوره‌</a></li>
@endsection

@section('content')
    <div class="main-content padding-0">
        <p class="box__title">بروزرسانی دوره‌</p>
        <div class="row no-gutters bg-white">
            <div class="col-12">
                <form action="{{ route('courses.update',$course->id) }}" method="post" class="padding-30"
                      enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <x-input type="text" name="title" value="{{ $course->title }}" placeholder="عنوان دوره" required/>
                    <x-input type="text" name="slug" value="{{ $course->slug }}" class="text-left" placeholder="نام انگلیسی دوره" required/>
                    <div class="d-flex multi-text">
                        <div class="mlg-15 flex-grow-1">
                            <x-input type="text" name="priority" value="{{ $course->priority }}" class="text-left" placeholder="ردیف دوره"/>
                        </div>
                        <div class="mlg-15 flex-grow-1">
                            <x-input type="number" name="price" value="{{ $course->price }}" class="text-left" placeholder="مبلغ دوره" required/>
                        </div>
                        <div class="flex-grow-1">
                            <x-input type="number" name="percent" value="{{ $course->percent }}" class="text-left" placeholder="درصد مدرس" required/>
                        </div>
                    </div>
                    <x-select name="teacher_id" required>
                        <option>انتخاب مدرس دوره</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @if($teacher->id == old('teacher_id') || $teacher->id == $course->teacher_id) selected @endif>{{ $teacher->name }}</option>
                        @endforeach
                    </x-select>
                    <x-tag-select placeholder="برچسب‌ها" />
                    <x-select name="type" required>
                        <option>نوع دوره</option>
                        @foreach(\Jokoli\Course\Enums\CourseType::asSelectArray() as $key=>$type)
                            <option value="{{ $key }}" @if((old('status') && $key == old('type')) || $key == $course->type) selected @endif>{{ $type }}</option>
                        @endforeach
                    </x-select>
                    <x-select name="status" required>
                        <option>وضعیت دوره</option>
                        @foreach(\Jokoli\Course\Enums\CourseStatus::asSelectArray() as $key=>$status)
                            <option value="{{ $key }}" @if((old('status') && $key == old('status')) || $key == $course->status) selected @endif>{{ $status }}</option>
                        @endforeach
                    </x-select>
                    <x-select name="category_id" required>
                        <option>دسته بندی</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @if($category->id == old('category_id') || $category->id == $course->category_id) selected @endif>{{ $category->title }}</option>
                        @endforeach
                    </x-select>
                    <x-file name="image" title="آپلود بنر دوره" :value="$course->banner"/>
                    <x-textarea name="body" placeholder="توضیحات دوره">{{ $course->body }}</x-textarea>
                    <button class="btn btn-webamooz_net">بروزرسانی</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('foot')
    <script src="{{ asset('panel/js/tagsInput.js') }}"></script>
@endsection
