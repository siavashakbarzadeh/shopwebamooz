<p class="box__title">ایجاد تخفیف جدید</p>
<form action="{{ route('discounts.store') }}" method="post" class="padding-30">
    @csrf
    <x-input type="text" name="code" class="text-left" placeholder="کد تخفیف"/>
    <x-input type="text" name="percent" class="text-left" placeholder="درصد تخفیف" required/>
    <x-input type="text" name="usage_limitation" class="text-left" placeholder="محدودیت افراد"/>
    <x-input type="text" name="expire_at" placeholder="تاریخ انقضاء" class="text js-date" autocomplete="off"/>
    <x-select name="courses[]" class="multiple width-100 dir-rtl" data-select-all="false" data-filter="true" multiple="multiple" placeholder="همه دوره ها">
        @foreach($courses as $course)
            <option value="{{ $course->id }}">{{ $course->title }}</option>
        @endforeach
    </x-select>
    <x-input type="text" name="link" class="text-left" placeholder="لینک اطلاعات بیشتر"/>
    <x-textarea name="description" placeholder="توضیحات"></x-textarea>
    <button class="btn btn-webamooz_net">اضافه کردن</button>
</form>
