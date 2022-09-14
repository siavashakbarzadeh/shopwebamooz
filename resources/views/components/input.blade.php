<label class="d-block width-100 mb-15">
    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name) ?? $value }}" {{ $attributes->merge(['class'=>'text mb-0'.($errors->has($name) ? ' is-invalid' : '')]) }}>
    <x-validation-error name="{{ $name }}"/>
    {{ $slot }}
</label>
