<p class="box__title">ایجاد نقش کاربری جدید</p>
<form action="{{ route('permissions.store') }}" method="post" class="padding-30">
    @csrf
    <label class="d-block width-100 mb-15">
        <input type="text" class="text mb-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"
               placeholder="نام نقش کاربری"
               required autofocus autocomplete="name">
        @error('name')
        <div class="invalid-feedback mt-4">
            <strong>{{ $message }}</strong>
        </div>
        @enderror
    </label>
    <p class="box__title margin-bottom-15">انتخاب مجوزها</p>
    <div class="mb-15">
        @foreach($permissions as $permission)
            <label class="ui-checkbox margin-bottom-5">
                <input type="checkbox" name="permissions[]" @if(old('permissions') && in_array($permission->id,old('permissions'))) checked @endif value="{{ $permission->id }}">
                <span class="checkmark position-relative margin-left-10"></span>
                {{ $permission->name_fa }}
            </label>
        @endforeach
        @error('permissions')
        <div class="invalid-feedback mt-4">
            <strong>{{ $message }}</strong>
        </div>
        @enderror
    </div>
    <button class="btn btn-webamooz_net">اضافه کردن</button>
</form>
