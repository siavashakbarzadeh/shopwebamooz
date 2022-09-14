@error(str_replace('[','.',str_replace(']','',$name)))
<div class="invalid-feedback mt-4">
    <strong>{{ $message }}</strong>
</div>
@enderror
