<?php

namespace Jokoli\Media\Contracts;

use Illuminate\Http\UploadedFile;
use Jokoli\Media\Models\Media;

interface FileServiceContract
{
    public static function upload(UploadedFile $file,string $filename,string $extension,string $disk): array;

    public static function delete(Media $media);

    public static function thumb(Media $media);

    public static function stream(Media $media);

    public static function getKeyDownload(): string;

}
