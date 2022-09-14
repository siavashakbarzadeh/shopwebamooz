<?php

namespace Jokoli\Media\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Jokoli\Media\Contracts\FileServiceContract;
use Jokoli\Media\Models\Media;

class ImageFileService extends DefaultFileService implements FileServiceContract
{
    private static array $sizes = [300, 600];

    public static function upload(UploadedFile $file, string $filename, string $extension, string $disk): array
    {
        $storage = Storage::disk($disk);
        $storage->putFileAs('', $file, $filename . '.' . $extension);
        return self::resize($storage, $filename, $extension);
    }

    public static function resize($storage, string $filename, string $extension)
    {
        $file = Image::make($storage->path($filename . '.' . $extension));
        $files['original'] = $filename . '.' . $extension;
        foreach (self::$sizes as $size) {
            $name = $filename . '_' . $size . '.' . $extension;
            $file->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($storage->path($name));
            $files[$size] = $name;
        }
        return $files;
    }

    public static function thumb(Media $media)
    {
        return Storage::disk($media->disk)->url($media->files['300']);
    }

    public static function getKeyDownload(): string
    {
        return 'original';
    }
}
