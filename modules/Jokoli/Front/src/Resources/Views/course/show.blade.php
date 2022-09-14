@extends('Front::layouts.master')
@section('title') {{ $course->title ." - ".env('APP_NAME') }} @endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endsection
@section('content')
    <main id="single">
        <div class="content">
            <div class="container">
                <article class="article">
                    @include('Front::layouts.header-ads')
                    <div class="h-t mt-10">
                        <h1 class="title">{{ $course->title }}</h1>
                        <div class="breadcrumb">
                            <ul>
                                <li><a href="{{ route('index') }}" title="خانه">خانه</a></li>
                                @if($course->category->parent)
                                    <li><a href="{{ $course->category->parent->path() }}"
                                           title="{{ $course->category->parent->title }}">{{ $course->category->parent->title }}</a>
                                    </li>
                                @endif
                                <li><a href="{{ $course->category->path() }}"
                                       title="{{ $course->category->title }}">{{ $course->category->title }}</a></li>
                            </ul>
                        </div>
                    </div>
                </article>
            </div>
            <div class="main-row container">
                <div class="sidebar-right">
                    <div class="sidebar-sticky">
                        <div class="product-info-box">
                            @if($course->getDiscountPercent())
                                <div class="discountBadge">
                                    <p>{{ $course->getDiscountPercent() }}%</p>
                                    تخفیف
                                </div>
                            @endif
                            <div class="sell_course">
                                <strong>قیمت</strong>
                                @if($course->getFinalPrice())
                                    @if($course->getDiscountPercent())
                                        <del class="discount-Price">{{ $course->getFromattedPrice() }}</del>
                                    @endif
                                    <p class="price">
                                    <span class="woocommerce-Price-amount amount">
                                        {{ $course->getFormattedFinalPrice() }}
                                        <span class="woocommerce-Price-currencySymbol">تومان</span>
                                    </span>
                                    </p>
                                @else
                                    <p class="price">رایگان</p>
                                @endif
                            </div>
                            @auth()
                                @if(auth()->id() == $course->teacher_id)
                                    <p class="mycourse">شما مدرس این دوره هستید</p>
                                @elseif(auth()->user()->can('access',$course))
                                    <p class="mycourse">شما این دوره رو خریداری کرده اید</p>
                                @else
                                    <button class="btn buy">خرید دوره</button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn text-white buy">ورود حساب کاربری</a>
                            @endauth
                            <div class="average-rating-sidebar">
                                <div class="rating-stars">
                                    <div class="slider-rating">
                                        <span class="slider-rating-span slider-rating-span-100" data-value="100%"
                                              data-title="خیلی خوب"></span>
                                        <span class="slider-rating-span slider-rating-span-80" data-value="80%"
                                              data-title="خوب"></span>
                                        <span class="slider-rating-span slider-rating-span-60" data-value="60%"
                                              data-title="معمولی"></span>
                                        <span class="slider-rating-span slider-rating-span-40" data-value="40%"
                                              data-title="بد"></span>
                                        <span class="slider-rating-span slider-rating-span-20" data-value="20%"
                                              data-title="خیلی بد"></span>
                                        <div class="star-fill"></div>
                                    </div>
                                </div>

                                <div class="average-rating-number">
                                    <span class="title-rate title-rate1">امتیاز</span>
                                    <div class="schema-stars">
                                        <span class="value-rate text-message"> 4 </span>
                                        <span class="title-rate">از</span>
                                        <span class="value-rate"> 555 </span>
                                        <span class="title-rate">رأی</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-info-box">
                            <div class="product-meta-info-list">
                                <div class="total_sales">
                                    تعداد دانشجو : <span>{{ $course->students_count }}</span>
                                </div>
                                <div class="meta-info-unit one">
                                    <span class="title">تعداد جلسات منتشر شده :  </span>
                                    <span class="vlaue">{{ $course->lessons_count }}</span>
                                </div>
                                <div class="meta-info-unit two">
                                    <span class="title">مدت زمان دوره : </span>
                                    <span class="vlaue">{{ $course->formatted_duration }}</span>
                                </div>
                                <div class="meta-info-unit four">
                                    <span class="title">مدرس دوره : </span>
                                    <span class="vlaue">{{ $course->teacher->name }}</span>
                                </div>
                                <div class="meta-info-unit five">
                                    <span class="title">وضعیت دوره : </span>
                                    <span class="vlaue">{{ $course->status_fa }}</span>
                                </div>
                                <div class="meta-info-unit six">
                                    <span class="title">پشتیبانی : </span>
                                    <span class="vlaue">دارد</span>
                                </div>
                            </div>
                        </div>
                        <div class="course-teacher-details">
                            <div class="top-part">
                                <a href="{{ $course->teacher->profilePath() }}">
                                    <img alt="{{ $course->teacher->name }}" class="img-fluid lazyloaded"
                                         src="{{ $course->teacher->thumb }}" loading="lazy">
                                    <noscript>
                                        <img class="img-fluid" src="{{ $course->teacher->thumb }}"
                                             alt="{{ $course->teacher->name }}"></noscript>
                                </a>
                                <div class="name">
                                    <a href="{{ $course->teacher->profilePath() }}" class="btn-link">
                                        <h6>{{ $course->teacher->name }}</h6>
                                    </a>
                                    <span class="job-title">{{ $course->teacher->headline }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="short-link">
                            <div class="">
                                <span>لینک کوتاه</span>
                                <input class="short--link dir-ltr"
                                       value="{{ preg_replace("(^https?://)", "", $course->shortPath() ) }}">
                                <a href="" class="short-link-a" data-link="{{ $course->shortPath() }}"></a>
                            </div>
                        </div>
                        @include('Front::layouts.sidebar-ads')
                    </div>
                </div>
                <div class="content-left">
                    @if($lesson)
                        @if($lesson->media->type == \Jokoli\Media\Enums\MediaType::Video)
                            <div class="preview">
                                <video width="100%" controls>
                                    <source src="{{ $lesson->downloadLink() }}" type="video/mp4">
                                </video>
                            </div>
                        @endif
                        <a href="{{ $lesson->downloadLink() }}" class="episode-download">دانلود این قسمت
                            (قسمت {{ $lesson->priority }})</a>
                    @endif
                    <div class="course-description">
                        <div class="course-description-title">توضیحات دوره
                            <div class="study-mode"></div>
                        </div>
                        <p class="">
                            {!! $course->body !!}
                        </p>
                        <div class="tags">
                            <ul>
                                <li><a href="">ری اکت</a></li>
                                <li><a href="">reactjs</a></li>
                                <li><a href="">جاوااسکریپت</a></li>
                                <li><a href="">javascript</a></li>
                                <li><a href="">reactjs چیست</a></li>
                            </ul>
                        </div>
                    </div>
                    @include('Front::layouts.episodes-list')
                </div>
            </div>
            <div class="container">
                <div class="comments">
                    <div class="comment-main">
                        <div class="ct-header">
                            <h3>نظرات ( 180 )</h3>
                            <p>نظر خود را در مورد این مقاله مطرح کنید</p>
                        </div>
                        <form action="" method="post">
                            <div class="ct-row">
                                <div class="ct-textarea">
                                    <textarea class="txt ct-textarea-field"></textarea>
                                </div>
                            </div>
                            <div class="ct-row">
                                <div class="send-comment">
                                    <button class="btn i-t">ثبت نظر</button>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="comments-list">
                        <div id="Modal2" class="modal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p>ارسال پاسخ</p>
                                    <div class="close">&times;</div>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="">
                                        <textarea class="txt hi-220px" placeholder="متن دیدگاه"></textarea>
                                        <button class="btn i-t">ثبت پاسخ</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <ul class="comment-list-ul">
                            <div class="div-btn-answer">
                                <button class="btn-answer">پاسخ به دیدگاه</button>
                            </div>
                            <li class="is-comment">
                                <div class="comment-header">
                                    <div class="comment-header-avatar">
                                        <img src="img/profile.jpg">
                                    </div>
                                    <div class="comment-header-detail">
                                        <div class="comment-header-name">کاربر : گوگل گوگل گوگل گوگل</div>
                                        <div class="comment-header-date">10 روز پیش</div>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    <p>
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان
                                        گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و
                                        برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای
                                        کاربردی می باشد.
                                    </p>
                                </div>
                            </li>
                            <li class="is-answer">
                                <div class="comment-header">
                                    <div class="comment-header-avatar">
                                        <img src="img/laravel-pic.png">
                                    </div>
                                    <div class="comment-header-detail">
                                        <div class="comment-header-name">مدیر سایت : محمد نیکو</div>
                                        <div class="comment-header-date">10 روز پیش</div>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    <p>
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان
                                        گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و
                                        برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای
                                        کاربردی می باشد.
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان
                                        گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و
                                        برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای
                                        کاربردی می باشد.
                                    </p>
                                </div>
                            </li>
                            <li class="is-comment">
                                <div class="comment-header">
                                    <div class="comment-header-avatar">
                                        <img src="img/profile.jpg">
                                    </div>
                                    <div class="comment-header-detail">
                                        <div class="comment-header-name">کاربر : گوگل</div>
                                        <div class="comment-header-date">10 روز پیش</div>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    <p>
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان
                                        گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و
                                        برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای
                                        کاربردی می باشد.
                                    </p>
                                </div>
                            </li>

                        </ul>
                        <ul class="comment-list-ul">
                            <div class="div-btn-answer">
                                <button class="btn-answer">پاسخ به دیدگاه</button>
                            </div>
                            <li class="is-comment">
                                <div class="comment-header">
                                    <div class="comment-header-avatar">
                                        <img src="img/profile.jpg">
                                    </div>
                                    <div class="comment-header-detail">
                                        <div class="comment-header-name">کاربر : گوگل</div>
                                        <div class="comment-header-date">10 روز پیش</div>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    <p>
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان
                                        گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و
                                        برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای
                                        کاربردی می باشد.
                                    </p>
                                </div>
                            </li>
                            <li class="is-answer">
                                <div class="comment-header">
                                    <div class="comment-header-avatar">
                                        <img src="img/laravel-pic.png">
                                    </div>
                                    <div class="comment-header-detail">
                                        <div class="comment-header-name">مدیر سایت : محمد نیکو</div>
                                        <div class="comment-header-date">10 روز پیش</div>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    <p>
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان
                                        گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و
                                        برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای
                                        کاربردی می باشد.
                                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان
                                        گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و
                                        برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای
                                        کاربردی می باشد.
                                    </p>
                                </div>
                            </li>

                        </ul>


                    </div>
                </div>
            </div>

        </div>
    </main>
    <div id="Modal3" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="text-dark">کد تخفیف را وارد کنید</div>
                <div class="close">&times;</div>
            </div>
            <div class="modal-body">
                <form action="{{ route('courses.buy',$course->id) }}" method="post">
                    @csrf
                    <label class="d-block width-100">
                        <input type="text" name="code" class="txt mb-0" autocomplete="off"
                               placeholder="کد تخفیف را وارد کنید">
                        <span class="d-block invalid-feedback mt-4 js-invalid-code"></span>
                    </label>
                    <div class="d-flex align-items-center">
                        <button class="btn i-t font-size-14" type="button" onclick="checkDiscountCode()">اعمال</button>
                        <div class="c-loading js-loading" style="display:none;"></div>
                    </div>
                    <table class="table text-center text-dark font-size-14 table-striped table-bordered">
                        <tbody>
                        <tr>
                            <td>قیمت کل دوره</td>
                            <td>{{ $course->formatted_price }} تومان</td>
                        </tr>
                        <tr>
                            <td>درصد تخفیف</td>
                            <td class="js-discount-percent">{{ $course->getDiscountPercent() }}%</td>
                        </tr>
                        <tr>
                            <td>مبلغ تخفیف</td>
                            <td class="text-red js-discount-amount">{{ $course->getFormattedDiscountAmount() }}تومان
                            </td>
                        </tr>
                        <tr>
                            <td>قابل پرداخت</td>
                            <td class="text-blue js-final-price">{{ $course->getFormattedFinalPrice() }} تومان</td>
                        </tr>
                        </tbody>
                    </table>
                    <button class="btn i-t font-size-14" type="submit">پرداخت آنلاین</button>
                </form>
            </div>
        </div>
    </div>
    <div class="toast">
        <div>
            <div class="toast__icon"></div>
            <div class="toast__message"></div>
            <div class="toast__close" onclick="toast__close()"></div>
        </div>
    </div>
@endsection
@section('foot')
    <script src="{{ asset('js/modal.js') }}"></script>
    <script>
        function checkDiscountCode() {
            const code = $('input[name="code"]').val().trim();
            if (code) {
                $.ajax({
                    url: '{{ route('discounts.check',$course->id) }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { code: code },
                    complete: function (jqXHR, textStatus, jqXHR2) {
                        $('.js-loading').hide();
                    },
                    beforeSend: function () {
                        $('.js-loading').show();
                    },
                    success: function (data, textStatus, jqXHR) {
                        $('.js-invalid-code').removeClass('text-error').addClass('text-success').text(data.message);
                        $('.js-discount-percent').text(data.discount_percent+"%");
                        $('.js-discount-amount').text(data.discount_amount+" تومان");
                        $('.js-final-price').text(data.final_price+" تومان");
                    },
                    error: function (jqXHR, textStatus, error) {
                        $('.js-invalid-code').removeClass('text-success').addClass('text-error').text(jqXHR.responseJSON.message ?? error);
                        $('.js-discount-percent').text(jqXHR.responseJSON.discount_percent+"%");
                        $('.js-discount-amount').text(jqXHR.responseJSON.discount_amount+" تومان");
                        $('.js-final-price').text(jqXHR.responseJSON.final_price+" تومان");
                    }
                });
            }
        }
    </script>
@endsection
