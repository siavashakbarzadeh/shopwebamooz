@extends('Dashboard::master')
@section('title') پیشخوان | @parent @endsection

@section('content')
    <div class="main-content">
        @can(\Jokoli\Permission\Enums\Permissions::Teach)
            <div class="row no-gutters font-size-13 margin-bottom-10">
                <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                    <p> موجودی حساب فعلی </p>
                    <p>{{ number_format(auth()->user()->balance) }} تومان</p>
                </div>
                <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                    <p> کل فروش دوره ها</p>
                    <p>{{ number_format($totalSales->total) }} تومان</p>
                </div>
                <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                    <p> کارمزد کسر شده </p>
                    <p>{{ number_format($totalSales->site_benefit) }} تومان</p>
                </div>
                <div class="col-3 padding-20 border-radius-3 bg-white margin-bottom-10">
                    <p> درآمد خالص </p>
                    <p>{{ number_format($totalSales->seller_benefit) }} تومان</p>
                </div>
            </div>
            <div class="row no-gutters font-size-13 margin-bottom-10">
                <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                    <p> درآمد امروز </p>
                    <p>{{ number_format($todaySales->seller_benefit) }} تومان</p>
                </div>
                <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                    <p> درآمد 30 روز گذاشته</p>
                    <p>{{ number_format($last30DaysUserBenefit) }} تومان</p>
                </div>
                <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
                    <p> تسویه حساب در حال انجام </p>
                    <p>0 تومان </p>
                </div>
                <div class="col-3 padding-20 border-radius-3 bg-white  margin-bottom-10">
                    <p>تراکنش های موفق امروز ({{ $todaySales->sales_count }}) تراکنش </p>
                    <p>{{ number_format($todaySales->total) }} تومان</p>
                </div>
            </div>
            <div class="row no-gutters font-size-13 margin-bottom-10">
                <div class="col-8 padding-20 bg-white margin-bottom-10 margin-left-10 border-radius-3">
                    <div id="container"></div>
                </div>
                <div class="col-4 info-amount padding-20 bg-white margin-bottom-12-p margin-bottom-10 border-radius-3">

                    <p class="title icon-outline-receipt">موجودی قابل تسویه </p>
                    <p class="amount-show color-444">{{ auth()->user()->balance }}<span> تومان </span></p>
                    <p class="title icon-sync">در حال تسویه</p>
                    <p class="amount-show color-444">0<span> تومان </span></p>
                    <a href="/" class=" all-reconcile-text color-2b4a83">همه تسویه حساب ها</a>
                </div>
            </div>
        @endcan
        <div class="row bg-white no-gutters font-size-13">
            <div class="title__row">
                <p>تراکنش های اخیر شما</p>
                <a href="{{ route('purchases.index') }}" class="all-reconcile-text margin-left-20 color-2b4a83">نمایش
                    همه تراکنش ها</a>
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
                    @foreach($last5Payments as $payment)
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
                    data: [@foreach($last30Dates as $date) {{ intval(optional($last30DaysSales->firstWhere('date',$date))->site_benefit) }}, @endforeach]
                }, {
                    type: 'column',
                    name: 'تراکنش موفق',
                    data: [@foreach($last30Dates as $date) {{ intval(optional($last30DaysSales->firstWhere('date',$date))->total) }}, @endforeach]
                }, {
                    type: 'column',
                    name: 'درآمد فروشنده',
                    color:'pink',
                    data: [@foreach($last30Dates as $date) {{ intval(optional($last30DaysSales->firstWhere('date',$date))->seller_benefit) }}, @endforeach]
                }, {
                    type: 'spline',
                    name: 'میزان فروش',
                    data: [@foreach($last30Dates as $date) {{ intval(optional($last30DaysSales->firstWhere('date',$date))->total) }}, @endforeach],
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
                        y: {{ $last30DaysUserBenefit }},
                        color: 'green' // Jane's color
                    }, {
                        name: 'درآمد فروشنده',
                        y: {{ $last30DaysSiteBenefit }},
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
