@extends('Dashboard::master')
@section('title') مدیریت تیکت ها | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('tickets.index') }}" title="مدیریت تیکت ها">مدیریت تیکت ها</a></li>
@endsection

@section('content')
    <div class="main-content tickets">
        <div class="tab__box">
            <div class="tab__items">
                <a class="tab__item @can(\Jokoli\Permission\Enums\Permissions::ManageTickets) @if(!\Jokoli\Ticket\Enums\TicketAnswerStatus::hasKey(ucfirst(request('status')))) is-active @endif @else is-active @endcan" href="{{ route('tickets.index',request()->except(['status','page'])) }}">همه تیکت ها</a>
                @can(\Jokoli\Permission\Enums\Permissions::ManageTickets)
                    <a class="tab__item @if(\Jokoli\Ticket\Enums\TicketAnswerStatus::New()->hasStatus(request('status'))) is-active @endif" href="{{ route('tickets.index',\Jokoli\Ticket\Enums\TicketAnswerStatus::New()->getParameters(request()->except('page'))) }}">جدید ها (خوانده نشده)</a>
                    <a class="tab__item @if(\Jokoli\Ticket\Enums\TicketAnswerStatus::Answered()->hasStatus(request('status'))) is-active @endif" href="{{ route('tickets.index',\Jokoli\Ticket\Enums\TicketAnswerStatus::Answered()->getParameters(request()->except('page'))) }}">پاسخ داده شده ها</a>
                @endcan
                <a class="tab__item " href="{{ route('tickets.create') }}">ارسال تیکت جدید</a>
            </div>
        </div>
        @can(\Jokoli\Permission\Enums\Permissions::ManageTickets)
            <div class="bg-white padding-20">
                <div class="t-header-search">
                    <form action="">
                        <div class="t-header-searchbox font-size-13">
                            @if(request()->filled('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                            <input type="text" class="text search-input__box font-size-13"
                                   placeholder="جستجوی در تیکت ها">
                            <div class="t-header-search-content ">
                                <input type="text" name="email" value="{{ request('email') }}" class="text" placeholder="ایمیل">
                                <input type="text" name="name" value="{{ request('name') }}" class="text " placeholder="نام و نام خانوادگی">
                                <input type="text" name="date" value="{{ request('date') }}" class="text margin-bottom-20" placeholder="تاریخ">
                                <button class="btn btn-webamooz_net">جستجو</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endcan
        <div class="table__box">
            <table class="table">
                <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th>شناسه</th>
                    <th>عنوان</th>
                    <th>نام ارسال کننده</th>
                    <th>ایمیل ارسال کننده</th>
                    <th>تاریخ آخرین پاسخ</th>
                    <th>وضعیت پاسخ</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tickets as $ticket)
                    <tr role="row" @if($ticket->isClosed()) class="close-status" @endif>
                        <td>{{ $ticket->id }}</td>
                        <td><a href="{{ route('tickets.show',$ticket->id) }}">{{ $ticket->title }}</a></td>
                        <td><a href="{{ $ticket->user->infoPath() }}" target="_blank">{{ $ticket->user->name }}</a></td>
                        <td>{{ $ticket->user->email }}</td>
                        <td>{{ verta($ticket->latest_reply_created_at)->formatJalaliDatetime() }}</td>
                        <td class="{{ $ticket->getAnswerStatusCssClass() }}">{{ $ticket->getAnswerStatus() }}</td>
                        <td class="js-text-status {{ $ticket->getStatusCssClass() }}">{{ $ticket->status_fa }}</td>
                        <td>
                            @can(\Jokoli\Permission\Enums\Permissions::ManageTickets)
                                <a href=""
                                   onclick="deleteItem(event,'{{ route('tickets.destroy',$ticket->id) }}')"
                                   class="item-delete mlg-15" title="حذف"></a>
                            @endcan
                            <a href=""
                               onclick="updateConfirmationStatus(event,'{{ route('tickets.close',$ticket->id) }}','js-text-status')"
                               class="item-reject mlg-15" title="بستن تیکت"></a>
                            <a href="{{ route('tickets.show',$ticket->id) }}" target="_blank" class="item-eye mlg-15"
                               title="مشاهده"></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
