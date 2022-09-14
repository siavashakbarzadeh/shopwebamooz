<?php

namespace Jokoli\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jokoli\Media\Repository\MediaRepository;
use Jokoli\Media\Services\MediaFileService;

class MediaController extends Controller
{
    public function download(MediaRepository $mediaRepository, Request $request, $media)
    {
        if (!$request->hasValidSignature()) abort(401);
        $media = $mediaRepository->findOrFailById($media);
        return MediaFileService::stream($media);
    }
}
