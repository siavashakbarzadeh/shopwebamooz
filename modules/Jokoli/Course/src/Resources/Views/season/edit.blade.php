@extends('Dashboard::master')
@section('title') بروزرسانی فصل | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="مدیریت دوره‌ها">مدیریت دوره‌ها</a></li>
    <li><a href="{{ route('courses.details',$season->course_id) }}" title="جزئیات دوره‌">جزئیات دوره‌</a></li>
    <li><a title="بروزرسانی فصل">بروزرسانی فصل</a></li>
@endsection

@section('content')
    <div class="main-content padding-0 categories">
        <div class="row no-gutters">
            <div class="col-6 bg-white">
                <p class="box__title">بروزرسانی فصل</p>
                <form action="{{ route('seasons.update',$season->id) }}" method="post" class="padding-30">
                    @csrf
                    @method('patch')
                    <x-input type="text" name="title" value="{{ $season->title }}" placeholder="عنوان سرفصل" required/>
                    <x-input type="text" name="priority" value="{{ $season->priority }}" placeholder="شماره سرفصل"/>
                    <button class="btn btn-webamooz_net">بروزرسانی</button>
                </form>
            </div>
        </div>
    </div>
@endsection
