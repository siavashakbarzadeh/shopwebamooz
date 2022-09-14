@extends('Dashboard::master')
@section('title') درخواست‌های تسویه | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('settlements.index') }}" title="درخواست‌های تسویه">درخواست‌های تسویه</a></li>
@endsection

@section('content')
    <div class="main-content">
        <div class="tab__box">
            <div class="tab__items">
                <a class="tab__item @if(!\Jokoli\Payment\Enums\SettlementStatus::hasKey(ucfirst(request('status')))) is-active @endif"
                   href="{{ route('settlements.index',request()->except(['status','page'])) }}">همه تسویه ها</a>
                <a class="tab__item @if(\Jokoli\Payment\Enums\SettlementStatus::Pending()->hasStatus(request('status'))) is-active @endif"
                   href="{{ route('settlements.index',\Jokoli\Payment\Enums\SettlementStatus::Pending()->getParameters(request()->except('page'))) }}">
                    تسویه های جدید
                </a>
                <a class="tab__item @if(\Jokoli\Payment\Enums\SettlementStatus::Settled()->hasStatus(request('status'))) is-active @endif"
                   href="{{ route('settlements.index',\Jokoli\Payment\Enums\SettlementStatus::Settled()->getParameters(request()->except('page'))) }}">
                    تسویه های واریز شده
                </a>
                <a class="tab__item" href="{{ route('settlements.create') }}">درخواست تسویه جدید</a>
            </div>
        </div>
        <div class="bg-white padding-20">
            <div class="t-header-search">
                <form action="">
                    <div class="t-header-searchbox font-size-13">
                        <input type="text" class="text search-input__box font-size-13"
                               placeholder="جستجوی در تسویه حساب ها">
                        <div class="t-header-search-content ">
                            @if(request()->filled('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                            <input type="text" name="card" value="{{ request('card') }}" class="text text-left"
                                   placeholder="شماره کارت">
                            <input type="text" name="transaction_id" value="{{ request('transaction_id') }}"
                                   class="text text-left" placeholder="شماره">
                            <input type="text" name="date" value="{{ request('date') }}" class="text text-left"
                                   placeholder="تاریخ">
                            <input type="text" name="email" value="{{ request('email') }}" class="text text-left"
                                   placeholder="ایمیل">
                            <input type="text" name="name" value="{{ request('name') }}" class="text margin-bottom-20"
                                   placeholder="نام و نام خانوادگی">
                            <button type="submit" class="btn btn-webamooz_net">جستجو</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table__box">
            <table class="table">
                <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th class="white-space-nowrap">ردیف</th>
                    <th class="white-space-nowrap">ثبت شده توسط</th>
                    <th class="white-space-nowrap">شناسه تسویه</th>
                    <th class="white-space-nowrap">مبدا</th>
                    <th class="white-space-nowrap">مقصد</th>
                    <th class="white-space-nowrap">شماره کارت مقصد</th>
                    <th class="white-space-nowrap">تاریخ درخواست واریز</th>
                    <th class="white-space-nowrap">مبلغ</th>
                    <th class="white-space-nowrap">وضعیت</th>
                    <th class="white-space-nowrap">تاریخ واریز</th>
                    @can(\Jokoli\Permission\Enums\Permissions::ManageSettlements)
                        <th class="white-space-nowrap">عملیات</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach($settlements as $settlement)
                    <tr role="row">
                        <td>{{ $settlements->perPage()*($settlements->currentPage()-1)+$loop->iteration }}</td>
                        <td>
                            <a href="{{ $settlement->user->infoPath() }}"
                               target="_blank">{{ $settlement->user->name }}</a>
                        </td>
                        <td>{{ $settlement->transaction_id ?? "-" }}</td>
                        <td>{{ $settlement->from['name'] }}</td>
                        <td>{{ optional($settlement->to)['name'] ?? "-" }}</td>
                        <td>{{ optional($settlement->to)['card_number'] ?? "-" }}</td>
                        <td>{{ verta($settlement->created_at)->formatJalaliDatetime() }}</td>
                        <td>{{ number_format($settlement->amount)." تومان" }}</td>
                        <td class="{{ $settlement->getStatusCssClass() }}">{{ $settlement->status_fa }}</td>
                        <td>{{ $settlement->settled_at ? verta($settlement->settled_at)->formatJalaliDatetime() : "-" }}</td>
                        @can(\Jokoli\Permission\Enums\Permissions::ManageSettlements)
                            <td>
                                <a href="{{ route('settlements.edit',$settlement->id) }}" class="item-edit "
                                   title="ویرایش"></a>
                            </td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="py-8">
            {{ $settlements->links() }}
        </div>
    </div>
@endsection
