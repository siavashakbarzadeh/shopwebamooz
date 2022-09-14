@extends('Dashboard::master')
@section('title') درخواست تسویه | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('settlements.index') }}" title="درخواست‌های تسویه">درخواست‌های تسویه</a></li>
    <li><a title="درخواست تسویه">درخواست تسویه</a></li>
@endsection

@section('content')
    <div class="main-content">
        <form action="{{ route('settlements.store') }}" method="post" class="padding-30 bg-white font-size-14">
            @csrf
            <x-input type="text" name="from[name]" value="{{ auth()->user()->name }}" placeholder="نام صاحب حساب" />
            <x-input type="text" name="from[card_number]" value="{{ auth()->user()->card_number }}" class="text-left" placeholder="شماره کارت بانکی"/>
            <x-input type="text" name="amount" value="{{ auth()->user()->balance }}" class="text-left" placeholder="مبلغ" />
            <div class="row no-gutters border-2 margin-bottom-15 text-center ">
                <div class="w-50 padding-20 w-50">موجودی قابل برداشت :‌</div>
                <div class="bg-fafafa padding-20 w-50"> {{ number_format(auth()->user()->balance) }} تومان</div>
            </div>
            <div class="row no-gutters border-2 text-center margin-bottom-15">
                <div class="w-50 padding-20">حداکثر زمان واریز :‌</div>
                <div class="w-50 bg-fafafa padding-20">۳ روز</div>
            </div>
            <button class="btn btn-webamooz_net">درخواست تسویه</button>
        </form>
    </div>
@endsection
