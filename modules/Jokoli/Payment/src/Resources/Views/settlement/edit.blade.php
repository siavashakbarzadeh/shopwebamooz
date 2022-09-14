@extends('Dashboard::master')
@section('title') بروزرسانی تسویه | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('settlements.index') }}" title="درخواست‌های تسویه">درخواست‌های تسویه</a></li>
    <li><a title="بروزرسانی تسویه">بروزرسانی تسویه</a></li>
@endsection

@section('content')
    <div class="main-content">
        <form action="{{ route('settlements.update',$settlement->id) }}" method="post" class="padding-30 bg-white font-size-14">
            @csrf
            @method('patch')
            <x-input type="text" name="from[name]" value="{{ optional($settlement->from)['name'] }}" placeholder="نام صاحب حساب فرستنده" />
            <x-input type="text" name="from[card_number]" value="{{ optional($settlement->from)['card_number'] }}" class="text-left" placeholder="شماره کارت بانکی فرستنده"/>
            <x-input type="text" name="to[name]" value="{{ optional($settlement->to)['name'] }}" placeholder="نام صاحب حساب گیرنده" />
            <x-input type="text" name="to[card_number]" value="{{ optional($settlement->to)['card_number'] }}" class="text-left" placeholder="شماره کارت بانکی گیرنده"/>
            <x-input type="text" name="amount" value="{{ number_format($settlement->amount) }}" class="text-left" placeholder="مبلغ" disabled />
            <x-select name="status" required>
                <option>وضعیت درخواست تسویه</option>
                @foreach(\Jokoli\Payment\Enums\SettlementStatus::asSelectArray() as $key=>$type)
                    <option value="{{ $key }}" @if((old('status') && $key == old('status')) || $key == $settlement->status) selected @endif>{{ $type }}</option>
                @endforeach
            </x-select>
            <div class="row no-gutters border-2 margin-bottom-15 text-center ">
                <div class="w-50 padding-20 w-50">موجودی حساب :‌</div>
                <div class="bg-fafafa padding-20 w-50"> {{ number_format($settlement->user->balance) }} تومان</div>
            </div>
            <button class="btn btn-webamooz_net">بروزرسانی تسویه</button>
        </form>
    </div>
@endsection
