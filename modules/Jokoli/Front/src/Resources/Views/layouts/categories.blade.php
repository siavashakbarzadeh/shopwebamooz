<ul class="nav">
    @foreach($categories as $category)
        <li class="main-menu @if($category->subCategories->count()) has-sub @endif">
            <a href="{{ $category->path() }}">{{ $category->title }}</a>
            @if($category->subCategories->count())
                <div class="sub-menu">
                    <div class="container">
                        @foreach($category->subCategories as $subCategory)
                            <div>
                                <a href="{{ $subCategory->path() }}">{{ $subCategory->title }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="triangle"></div>
            @endif
        </li>
    @endforeach
    <li class="main-menu d-none"><a href="#">درباره ما</a></li>
    <li class="main-menu"><a href="contact-us.html">تماس ما</a></li>
    <li class="main-menu join-teachers-li"><a href="become-a-teacher.html">تدریس در وب آموز</a></li>
    <li class="main-menu"><a href="https://www.webamooz.net/blog">مقالات</a></li>
</ul>
