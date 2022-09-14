@extends('Dashboard::master')
@section('title') مدیریت تراکنش‌ها | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('payments.index') }}" title="مدیریت تراکنش‌ها">مدیریت تراکنش‌ها</a></li>
@endsection

@section('content')
    <div class="main-content font-size-13">
        <div class="row no-gutters  margin-bottom-10">
            <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                <p>کل فروش ۳۰ روز گذشته سایت </p>
                <p>{{ number_format($last30DaysSales->total) }} تومان</p>
            </div>
            <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                <p>درامد خالص ۳۰ روز گذشته سایت</p>
                <p>{{ number_format($last30DaysSales->site_benefit) }} تومان</p>
            </div>
            <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                <p>کل فروش سایت</p>
                <p>{{ number_format($allSales->total) }} تومان</p>
            </div>
            <div class="col-3 padding-20 border-radius-3 bg-white margin-bottom-10">
                <p> کل درآمد خالص سایت</p>
                <p>{{ number_format($allSales->site_benefit) }} تومان</p>
            </div>
        </div>
        <div class="row no-gutters border-radius-3 font-size-13">
            <div class="col-12 bg-white padding-30 margin-bottom-20">
                <div id="container"></div>
            </div>
        </div>
        <div class="d-flex flex-space-between item-center flex-wrap padding-30 border-radius-3 bg-white">
            <p class="margin-bottom-15">همه تراکنش ها</p>
            <div class="t-header-search">
                <form action="">
                    <div class="t-header-searchbox font-size-13">
                        <input type="text" class="text search-input__box font-size-13" placeholder="جستجوی تراکنش">
                        <div class="t-header-search-content ">
                            <input type="text" class="text text-left" name="email" value="{{ request('email') }}" placeholder="ایمیل">
                            <input type="text" class="text text-left" name="amount" value="{{ request('amount') }}" placeholder="مبلغ به تومان">
                            <input type="text" class="text text-left" name="invoice_id" value="{{ request('invoice_id') }}" placeholder="شماره">
                            <input type="text" class="text text-left" name="from" value="{{ request('from') }}" placeholder="از تاریخ : 1399/01/01">
                            <input type="text" class="text text-left margin-bottom-20" name="to" value="{{ request('to') }}" placeholder="تا تاریخ : 1399/10/12">
                            <button type="submit" class="btn btn-webamooz_net">جستجو</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table__box">
            <table width="100%" class="table">
                <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th>شناسه</th>
                    <th>شماره تراکنش</th>
                    <th>نام و نام خانوادگی</th>
                    <th>ایمیل پرداخت کننده</th>
                    <th>مبلغ</th>
                    <th>درامد مدرس</th>
                    <th>درامد سایت</th>
                    <th>نام دوره</th>
                    <th>تاریخ و ساعت</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $payment)
                    <tr role="row">
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->invoice_id }}</td>
                        <td>
                            <a @if($payment->buyer->username) href="{{ $payment->buyer->profilePath() }}"
                               target="_blank" @endif>{{ $payment->buyer->name }}</a>
                        </td>
                        <td>{{ $payment->buyer->email }}</td>
                        <td>{{ number_format($payment->amount) }} تومان</td>
                        <td>{{ number_format($payment->seller_share) }} تومان</td>
                        <td>{{ number_format($payment->site_share) }} تومان</td>
                        <td>
                            <a href="{{ $payment->paymentable->path() }}"
                               target="_blank">{{ $payment->paymentable->title }}</a>
                        </td>
                        <td>{{ verta($payment->created_at)->formatJalaliDatetime() }}</td>
                        <td class="{{ $payment->getStatusCssClass() }}">{{ $payment->status_fa }}</td>
                        <td>
                            <a href="" class="item-delete mlg-15"></a>
                            <a href="edit-transaction.html" class="item-edit"></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="py-8">
            {{ $payments->links() }}
        </div>
    </div>
@endsection
@section('foot')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        $(function () {
            Highcharts.chart('container', {
                title: {
                    text: 'نمودار درآمد 30 روز گذشته',
                    style: {
                        fontSize: 14
                    }
                },
                chart: {
                    style: {
                        fontFamily: 'irs'
                    }
                },
                yAxis: {
                    title: false,
                    labels: {
                        useHTML: true,
                        formatter: function () {
                            return '<div class="dir-rtl">' + (this.value ? this.value.toLocaleString() + ' تومان' : this.value) + '</div>';
                        }
                    }
                },
                xAxis: {
                    categories: [@foreach($last30Dates as $date) '{{ verta($date)->formatJalaliDate() }}', @endforeach]
                },
                tooltip: {
                    useHTML: true,
                    formatter: function () {
                        return '<div class="font-size-12 dir-rtl">' +
                            '<div class="font-size-11 text-right">' + this.series.name + '</div>' +
                            '<div>' + this.y.toLocaleString() + ' تومان' + '</div>' +
                            ( this.x ? '<div>' + this.x + '</div>' : '' ) +
                            '</div>';
                    }
                },
                labels: {
                    items: [{
                        html: 'درآمد 30 روز گذشته',
                        style: {
                            left: '50px',
                            top: '18px',
                            color: ( // theme
                                Highcharts.defaultOptions.title.style &&
                                Highcharts.defaultOptions.title.style.color
                            ) || 'black'
                        }
                    }]
                },
                series: [{
                    type: 'column',
                    name: 'درآمد سایت',
                    color:'green',
                    data: [@foreach($last30Dates as $date) {{ intval(optional($last30DaysTransactions->firstWhere('date',$date))->site_benefit) }}, @endforeach]
                }, {
                    type: 'column',
                    name: 'تراکنش موفق',
                    data: [@foreach($last30Dates as $date) {{ intval(optional($last30DaysTransactions->firstWhere('date',$date))->total) }}, @endforeach]
                }, {
                    type: 'column',
                    name: 'درآمد فروشنده',
                    color:'pink',
                    data: [@foreach($last30Dates as $date) {{ intval(optional($last30DaysTransactions->firstWhere('date',$date))->seller_benefit) }}, @endforeach]
                }, {
                    type: 'spline',
                    name: 'میزان فروش',
                    data: [@foreach($last30Dates as $date) {{ intval(optional($last30DaysTransactions->firstWhere('date',$date))->total) }}, @endforeach],
                    marker: {
                        lineWidth: 2,
                        lineColor: 'green',
                        fillColor: 'white',
                    },
                    color:'green'
                }, {
                    type: 'pie',
                    name: 'فروش',
                    data: [{
                        name: 'درآمد سایت',
                        y: {{ $last30DaysSales->site_benefit }},
                        color: 'green' // Jane's color
                    }, {
                        name: 'درآمد فروشنده',
                        y: {{ $last30DaysSales->total - $last30DaysSales->site_benefit }},
                        color: 'pink' // John's color
                    }],
                    center: [75, 70],
                    size: 90,
                    showInLegend: false,
                    dataLabels: {
                        enabled: false
                    }
                }]
            });
        });
    </script>
@endsection
