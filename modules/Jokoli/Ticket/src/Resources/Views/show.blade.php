@extends('Dashboard::master')
@section('title') ایجاد تیکت | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('tickets.index') }}" title="مدیریت تیکت ها">مدیریت تیکت ها</a></li>
    <li><a title="مشاهده تیکت">مشاهده تیکت</a></li>
@endsection

@section('content')
    <div class="main-content">
        <div class="show-comment">
            <div class="ct__header">
                <div class="comment-info">
                    <a class="back" href="{{ url()->previous() }}"></a>
                    <div>
                        <p class="comment-name">{{ $ticket->title }}</p>
                    </div>
                </div>
            </div>
            @foreach($ticket->replies as $reply)
                <div class="transition-comment @if($reply->isAnswer()) is-answer @endif">
                    <div class="transition-comment-header">
                       <span>
                            <img src="{{ $reply->user->thumb }}" class="logo-pic">
                       </span>
                        <span class="nav-comment-status">
                            <p class="username">{{ $reply->user->name }}</p>
                            <p class="comment-date">{{ verta($reply->created_at)->formatJalaliDatetime().($loop->last ? " - ".$reply->created_at->diffForhumans() : null) }}</p></span>
                        <div>
                        </div>
                    </div>
                    <div class="transition-comment-body">
                        <div class="padding-30">
                            <pre class="p-0-important">{{ $reply->body }}</pre>
                            @if($reply->media_id)
                                <a href="{{ $reply->downloadLink() }}" class="font-size-12 mt-10">دریافت فایل پیوست</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="answer-comment">
            <p class="p-answer-comment">ارسال پاسخ</p>
            <form action="{{ route('tickets.reply',$ticket->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <x-textarea name="body" placeholder="متن پاسخ"></x-textarea>
                <x-file name="attachment" title="آپلود فایل پیوست"/>
                <button class="btn btn-webamooz_net">ارسال پاسخ</button>
            </form>
        </div>
    </div>
@endsection
