@extends('Dashboard::master')
@section('title') خریدهای من | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('purchases.index') }}" title="خریدهای من">خریدهای من</a></li>
@endsection

@section('content')
    <div class="main-content">
        <div class="table__box">
            <table class="table">
                <thead>
                <tr class="title-row">
                    <th>عنوان دوره</th>
                    <th>تاریخ پرداخت</th>
                    <th>مقدار پرداختی</th>
                    <th>وضعیت پرداخت</th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>
                            <a href="{{ $payment->paymentable->path() }}" target="_blank">{{ $payment->paymentable->title }}</a>
                        </td>
                        <td>{{ verta($payment->created_at)->formatJalaliDatetime() }}</td>
                        <td>{{ number_format($payment->amount) }} تومان</td>
                        <td class="{{ $payment->getStatusCssClass() }}">{{ $payment->status_fa }}</td>
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
