@extends('Dashboard::master')
@section('title') بروزرسانی تخفیف | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('discounts.index') }}" title="مدیریت تخفیف ها">مدیریت تخفیف ها</a></li>
    <li><a title="بروزرسانی تخفیف">بروزرسانی تخفیف</a></li>
@endsection

@section('content')
    <div class="main-content padding-0 categories">
        <div class="row no-gutters">
            <div class="col-6 bg-white">
                <p class="box__title">بروزرسانی تخفیف</p>
                <form action="{{ route('discounts.update',$discount->id) }}" method="post" class="padding-30">
                    @csrf
                    @method('patch')
                    <x-input type="text" name="code" value="{{ $discount->code }}" class="text-left" placeholder="کد تخفیف"/>
                    <x-input type="text" name="percent" value="{{ $discount->percent }}" class="text-left" placeholder="درصد تخفیف" required/>
                    <x-input type="text" name="usage_limitation" value="{{ $discount->usage_limitation }}" class="text-left" placeholder="محدودیت افراد"/>
                    <x-input type="text" name="expire_at" value="{{ $discount->expire_at }}" placeholder="تاریخ انقضاء" class="text js-date"
                             autocomplete="off"/>
                    <x-select name="courses[]" class="multiple width-100 dir-rtl" data-select-all="false"
                              data-filter="true"
                              multiple="multiple" placeholder="همه دوره ها">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" @if($discount->courses->contains($course->id)) selected @endif>{{ $course->title }}</option>
                        @endforeach
                    </x-select>
                    <x-input type="text" name="link" value="{{ $discount->link }}" class="text-left" placeholder="لینک اطلاعات بیشتر"/>
                    <x-textarea name="description" value="{{ $discount->description }}" placeholder="توضیحات"></x-textarea>
                    <button class="btn btn-webamooz_net">بروزرسانی</button>
                </form>
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
                initialValue: {{ $discount->expire_at ? 'true' : 'false' }},
                minDate: new persianDate().valueOf(),
                format: 'YYYY/MM/DD HH:mm:ss',
                timePicker: {
                    enabled: true
                }
            });
        });
    </script>
@endsection
