<?php

namespace Jokoli\Ticket\Services;

use Jokoli\Media\Services\MediaFileService;
use Jokoli\Ticket\Repository\TicketReplyRepository;

class TicketReplyService
{
    public static function store($ticket,$request)
    {
        $request->merge(['media_id' => $request->hasFile('attachment') ? MediaFileService::privateUpload($request->attachment)->id : null]);
        resolve(TicketReplyRepository::class)->store($ticket,$request);
    }
}
