<div class="d-block width-100 mb-15">
    <select name="{{ $name }}" {{ $attributes }}>
        {{ $slot }}
    </select>
    <x-validation-error name="{{ $name }}"/>
</div>
