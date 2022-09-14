<?php

namespace Jokoli\Media\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Jokoli\Media\Contracts\FileServiceContract;
use Jokoli\Media\Models\Media;

class ZipFileService extends DefaultFileService implements FileServiceContract
{
    public static function upload(UploadedFile $file, string $filename, string $extension, string $disk): array
    {
        Storage::disk($disk)->putFileAs('', $file, $filename . '.' . $extension);
        return ['zip' => $filename . '.' . $extension];
    }

    public static function thumb(Media $media)
    {
        return asset('img/zip-thumb.svg');
    }

    public static function getKeyDownload(): string
    {
        return 'zip';
    }
}
