<?php

namespace Jokoli\Media\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Jokoli\Media\Contracts\FileServiceContract;
use Jokoli\Media\Enums\MediaType;
use Jokoli\Media\Models\Media;

class MediaFileService
{
    private static $file;
    private static $disk;

    public static function privateUpload(UploadedFile $file)
    {
        self::$file = $file;
        self::$disk = 'private';
        return self::upload();
    }

    public static function publicUpload(UploadedFile $file)
    {
        self::$file = $file;
        self::$disk = 'public';
        return self::upload();
    }

    private static function upload()
    {
        $extension = self::normalizeExtension();
        $filename = self::fileNameGenerator();
        $service = collect(config('media.MediaTypeServices'))->first(function ($value) use ($extension) {
            return in_array($extension, $value['extensions']);
        });
        if ($service)
            return self::uploadByHandler(new $service['handler'], $service['type'], $filename, $extension);
        return null;
    }

    public static function stream(Media $media)
    {
        $extension = strtolower(File::extension(Storage::disk($media->disk)->path(array_first($media->files))));
        $service = collect(config('media.MediaTypeServices'))->first(function ($value) use ($extension) {
            return in_array($extension, $value['extensions']);
        });
        if ($service)
            return $service['handler']::stream($media);
        return null;
    }

    public static function delete($media)
    {
        $service = collect(config('media.MediaTypeServices'))->firstWhere('type', $media->type);
        if ($service) $service['handler']::delete($media);
        return null;
    }

    private static function normalizeExtension(): string
    {
        return strtolower(self::$file->getClientOriginalExtension());
    }

    private static function fileNameGenerator()
    {
        return uniqid() . auth()->id() . time();
    }

    private static function uploadByHandler(FileServiceContract $service, string $type, string $filename, string $extension): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
    {
        return Media::query()->create([
            'user_id' => auth()->id(),
            'files' => $service::upload(self::$file, $filename, $extension, self::$disk),
            'type' => $type,
            'disk' => self::$disk,
            'filename' => self::$file->getClientOriginalName(),
        ]);
    }

    public static function thumb(Media $media)
    {
        $service = collect(config('media.MediaTypeServices'))->firstWhere('type', $media->type);
        if ($service) return $service['handler']::thumb($media);
        return null;
    }

    public static function getExtensions()
    {
        return collect(config('media.MediaTypeServices'))->pluck('extensions')->flatten()->implode(',');
    }
}
