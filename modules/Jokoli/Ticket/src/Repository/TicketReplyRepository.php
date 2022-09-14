<?php

namespace Jokoli\Ticket\Repository;

use App\Models\TicketReply;
use Jokoli\Ticket\Models\Ticket;

class TicketReplyRepository
{
    private function query()
    {
        return TicketReply::query();
    }

    public function store($ticket, $request)
    {
        return $ticket->replies()->create([
            'user_id' => auth()->id(),
            'media_id' => $request->media_id,
            'body' => $request->body,
        ]);
    }
}
