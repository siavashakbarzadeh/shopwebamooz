@extends('Front::layouts.master')
@section('title') @endsection

@section('content')
    <main id="index">
        <div class="bt-0-top article mr-202"></div>
        <div class="bt-1-top">
            <div class="container">
                <div class="tutor">
                    <div class="tutor-item">
                        <div class="tutor-avatar">
                            <span class="tutor-image " id="tutor-image">
                                <img src="{{ $user->thumb }}" alt="{{ $user->name }}" class="tutor-avatar-img border-radius-circle">
                            </span>
                            <div class="tutor-author-name">
                                <a id="tutor-author-name" href="" title="{{ $user->name }}">
                                    <h3 class="title">
                                        <span class="tutor-author--name">{{ $user->name }}</span>
                                    </h3>
                                </a>
                            </div>
                            <div id="Modal1" class="modal">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="close">&times;</div>
                                    </div>
                                    <div class="modal-body">
                                        <img class="tutor--avatar--img" src="{{ $user->thumb }}" alt="{{ $user->name }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tutor-item">
                        <div class="stat">
                            <span class="tutor-number tutor-count-courses">{{ $user->courses_count }}</span>
                            <span class="">تعداد دوره ها</span>
                        </div>
                        <div class="stat">
                            <span class="tutor-number">{{ $user->students_count }}</span>
                            <span class="">تعداد  دانشجویان</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="box-filter">
                <div class="b-head">
                    <h2>دوره های {{ $user->name }}</h2>
                </div>
                <div class="posts">
                    @foreach($courses as $course)
                        @include('Front::layouts.course.single')
                    @endforeach
                </div>
            </div>
            <div class="py-8">
                {{ $courses->links() }}
            </div>
        </div>
    </main>
@endsection
@section('head')
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endsection
@section('foot')
    <script src="{{ asset('js/modal.js') }}"></script>
@endsection
