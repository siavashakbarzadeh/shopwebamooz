<div class="col">
    <a href="{{ $course->path() }}">
        <div class="course-status">{{ $course->status_fa }}</div>
        @if($course->getDiscountPercent())
            <div class="discountBadge">
                <p>{{ $course->getDiscountPercent() }}%</p>
                تخفیف
            </div>
        @endif
        <div class="card-img"><img src="{{ $course->banner->thumb }}" alt="{{ $course->title }}"></div>
        <div class="card-title">
            <h2>{{ $course->title }}</h2>
        </div>
        <div class="card-body">
            <img src="{{ $course->teacher->thumb }}" alt="{{ $course->teacher->name }}">
            <span>{{ $course->teacher->name }}</span>
        </div>
        <div class="card-details">
            <div class="time">{{ $course->formatted_duration }}</div>
            <div class="price">
                @if($course->getDiscountPercent())
                    <div class="discountPrice">{{ $course->getFromattedPrice() }}</div>
                @endif
                <div class="endPrice">{{ $course->getFormattedFinalPrice() }}</div>
            </div>
        </div>
    </a>
</div>
