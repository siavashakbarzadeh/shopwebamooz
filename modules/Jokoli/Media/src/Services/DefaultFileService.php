<?php

namespace Jokoli\Media\Services;

use Illuminate\Support\Facades\Storage;
use Jokoli\Media\Models\Media;

class DefaultFileService
{

    public static function delete(Media $media)
    {
        Storage::disk($media->disk)->delete($media->files);
    }

    public static function stream(Media $media)
    {
        $path = array_get($media->files,static::getKeyDownload());
        $stream = Storage::disk($media->disk)->readStream($path);
        return response()->stream(function () use ($stream) {
            while (ob_get_level() > 0) ob_end_flush();
            fpassthru($stream);
        }, 200, [
            'Content-Type' => Storage::disk($media->disk)->mimeType($path),
            'Content-disposition' => 'attachment;filename=' . $media->filename,
        ]);
    }
}
