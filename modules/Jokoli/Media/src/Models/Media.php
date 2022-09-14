<?php

namespace Jokoli\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Jokoli\Media\Database\Factories\MediaFactory;
use Jokoli\Media\Services\MediaFileService;

class Media extends Model
{
    use HasFactory;

    const Factory = MediaFactory::class;

    protected $guarded = [];

    protected $casts = [
        'files' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleted(function (Media $media) {
            MediaFileService::delete($media);
        });
    }

    public function getThumbAttribute()
    {
        return MediaFileService::thumb($this);
    }
}
