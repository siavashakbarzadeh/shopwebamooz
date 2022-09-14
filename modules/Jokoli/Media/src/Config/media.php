<?php

use Jokoli\Media\Enums\MediaType;

return [
    'MediaTypeServices' => [
        'image' => [
            'extensions' => ['png', 'jpg', 'jpeg'],
            'type' => MediaType::Image,
            'handler' => \Jokoli\Media\Services\ImageFileService::class
        ],
        'video' => [
            'extensions' => ['avi', 'mp4', 'mkv'],
            'type' => MediaType::Video,
            'handler' => \Jokoli\Media\Services\VideoFileService::class
        ],
        'zip'=>[
            'extensions' => ['zip', 'rar', 'tar'],
            'type' => MediaType::Zip,
            'handler' => \Jokoli\Media\Services\ZipFileService::class
        ]
    ],
];
