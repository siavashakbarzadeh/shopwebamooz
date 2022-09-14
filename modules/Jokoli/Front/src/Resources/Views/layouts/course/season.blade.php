<div class="episodes-list-group">
    <div class="episodes-list-group-head">
        <div class="head-right">
            <span class="section-name">بخش {{ notowo($loop->iteration,config('app.locale')) }} </span>
            <div class="section-title">{{ $season->title }}</div>
            <span class="episode-count"> {{ $season->accepted_lessons->count() }} ویدیو </span>
        </div>
        <div class="head-left"></div>
    </div>
    <div class="episodes-list-group-body">
        @foreach($season->accepted_lessons as $lesson)
            @include('Front::layouts.course.lesson')
        @endforeach
    </div>
</div>
