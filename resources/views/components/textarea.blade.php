<label class="d-block width-100 mb-15">
    <textarea name="{{ $name }}" {{ $attributes->merge(['class'=>'text h mb-0']) }}>{{ old($name) ?? $slot }}</textarea>
    <x-validation-error name="{{ $name }}"/>
</label>
