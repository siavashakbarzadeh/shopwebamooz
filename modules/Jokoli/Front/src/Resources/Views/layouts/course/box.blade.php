<div class="box-filter">
    <div class="b-head">
        <h2>{{ $title }}</h2>
        @isset($url)
            <a href="{{ $url }}">مشاهده همه</a>
        @endisset
    </div>
    <div class="posts">
        @foreach($courses as $course)
            @include('Front::layouts.course.single')
        @endforeach
    </div>
</div>
