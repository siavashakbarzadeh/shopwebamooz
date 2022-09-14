<div class="episodes-list">
    <div class="episodes-list--title d-flex justify-content-between">
        <div class="">فهرست جلسات</div>
        @can('access',$course)
            <a href="{{ route('courses.download-links',$course->id) }}" class="color-46b2f0 font-size-13">دریافت همه لینک های دانلود</a>
        @endcan
    </div>
    <div class="episodes-list-section">
        @if($course->seasons->count())
            @foreach($course->seasons as $season)
                @include('Front::layouts.course.season')
            @endforeach
        @else
            @foreach($course->lessons as $lesson)
                @include('Front::layouts.course.lesson')
            @endforeach
        @endif
    </div>
</div>
