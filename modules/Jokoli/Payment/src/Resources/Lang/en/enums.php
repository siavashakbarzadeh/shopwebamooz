<?php

use Jokoli\Payment\Enums\PaymentStatus;
use Jokoli\Payment\Enums\SettlementStatus;

return [
    PaymentStatus::class => [
        PaymentStatus::Pending => "pending",
        PaymentStatus::Canceled => "canceled",
        PaymentStatus::Success => "success",
        PaymentStatus::Fail => "fail",
    ],
    SettlementStatus::class => [
        SettlementStatus::Pending => "pending",
        SettlementStatus::Canceled => "canceled",
        SettlementStatus::Settled => "settled",
        SettlementStatus::Rejected => "rejected",
    ],
];
