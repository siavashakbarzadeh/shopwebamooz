@extends('Front::layouts.master')

@section('content')
    <main id="index">
        <article class="container article">
            @include('Front::layouts.header-ads')
            @include('Front::layouts.top-info')
            @include('Front::layouts.course.box',['title'=>"جدیدترین دوره‌ها",'url'=>route('home'),'courses'=>$latestCourses])
            @include('Front::layouts.course.box',['title'=>"پر مخاطب ترین دوره ها",'url'=>route('home'),'courses'=>$mostPopularCourses])
        </article>
        @include('Front::layouts.articles')
    </main>
@endsection
