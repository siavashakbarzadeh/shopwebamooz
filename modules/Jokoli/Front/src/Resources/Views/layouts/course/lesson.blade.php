<div
    class="episodes-list-item @cannot('access',$lesson) 'lock' @endcannot">
    <div class="section-right">
        <span class="episodes-list-number">{{ $lesson->priority }}</span>
        <div class="episodes-list-title">
            <a href="{{ $lesson->path() }}">{{ $lesson->title }}</a>
        </div>
    </div>
    <div class="section-left">
        <div class="episodes-list-details">
            <div class="episodes-list-details">
                @if($lesson->is_free)
                    <span class="detail-type">رایگان</span>
                @endif
                <span class="detail-time">{{ $lesson->formatted_duration }}</span>
                @can('access',$lesson)
                    <a class="detail-download" href="{{ $lesson->downloadLink() }}">
                        <i class="icon-download"></i>
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>
