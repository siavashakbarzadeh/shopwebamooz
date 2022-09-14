<?php

use Jokoli\Ticket\Enums\TicketStatus;

return [
    TicketStatus::class => [
        TicketStatus::Pending => "در حال بررسی",
        TicketStatus::Open => "باز شده",
        TicketStatus::Close => "بسته شده",
    ],
];
