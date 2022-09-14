<?php

use Jokoli\Ticket\Enums\TicketStatus;

return [
    TicketStatus::class => [
        TicketStatus::Pending => "text-warning",
        TicketStatus::Open => "text-info",
        TicketStatus::Close => null,
    ],
    'answered' => "text-opacity-low",
    'not_answered' => "text-info",
];
