<?php

namespace Jokoli\Media\Repository;

use Jokoli\Media\Models\Media;

class MediaRepository
{
    private function query()
    {
        return Media::query();
    }

    public function findOrFailById($media)
    {
        return $this->query()->findOrFail($media);
    }

    public function findById($media)
    {
        return $this->query()->find($media);
    }
}
