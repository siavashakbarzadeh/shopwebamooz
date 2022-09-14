<div class="mb-15">
    <div class="file-upload mb-0">
        <div class="i-file-upload">
            <span>{{ $title }}</span>
            <input type="file" class="file-upload" id="files" name="{{ $name }}"/>
        </div>
        <span class="filesize p-0"></span>
        <div class="pt-6 d-flex align-items-end">
            @if($value)
            <img src="{{ $value->thumb }}" width="80" alt="{{ $value->filename }}">
            @endif
            <span class="selectedFiles mr-4 p-0">{{ optional($value)->filename ?? "فایلی انتخاب نشده است" }}</span>
        </div>
    </div>
    <x-validation-error name="{{ $name }}"/>
</div>
