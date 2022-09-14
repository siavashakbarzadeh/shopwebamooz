@extends('Dashboard::master')
@section('title') مدیریت تخفیف ها | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('discounts.index') }}" title="مدیریت تخفیف ها">مدیریت تخفیف ها</a></li>
@endsection

@section('content')
    <div class="main-content padding-0 discounts">
        <div class="row no-gutters  ">
            <div class="col-8 margin-left-10 margin-bottom-15 border-radius-3">
                <p class="box__title">مدیریت تخفیف ها</p>
                <div class="table__box">
                    <div class="table-box">
                        <table class="table">
                            <thead role="rowgroup">
                            <tr role="row" class="title-row">
                                <th>شناسه</th>
                                <th>کد</th>
                                <th>درصد</th>
                                <th>محدودیت زمانی</th>
                                <th>توضیحات</th>
                                <th>استفاده شده</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($discounts as $discount)
                            <tr role="row" class="">
                                <td>{{ $discount->id }}</td>
                                <td>{{ $discount->code ?? "-" }}</td>
                                <td>{{ $discount->percent }}% برای {{ $discount->courses_count ? "دوره های خاص" : "همه دوره ها" }}</td>
                                <td>{{ $discount->expire_at ? verta($discount->expire_at)->formatJalaliDatetime() : "بدون محدودیت" }}</td>
                                <td>{{ $discount->description ?? "-" }}</td>
                                <td>{{ $discount->uses }} نفر</td>
                                <td>
                                    <a href=""
                                       onclick="deleteItem(event,'{{ route('discounts.destroy',$discount->id) }}')"
                                       class="item-delete mlg-15" title="حذف"></a>
                                    <a href="{{ route('discounts.edit',$discount->id) }}" class="item-edit " title="ویرایش"></a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-4 bg-white">
                @include('Discount::create')
            </div>
        </div>
    </div>
@endsection
@section('head')
    <link rel="stylesheet" href="{{ asset('panel/css/multiple-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('panel/css/persian-datepicker.css') }}">
@endsection
@section('foot')
    <script src="{{ asset('panel/js/multiple-select.min.js') }}"></script>
    <script src="{{ asset('panel/js/persian-date.min.js') }}"></script>
    <script src="{{ asset('panel/js/persian-datepicker.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('select').multipleSelect();
            $('.js-date').persianDatepicker({
                initialValue: false,
                minDate: new persianDate().valueOf(),
                format: 'YYYY/MM/DD HH:mm:ss',
                timePicker: {
                    enabled: true
                }
            });
        });
    </script>
@endsection
