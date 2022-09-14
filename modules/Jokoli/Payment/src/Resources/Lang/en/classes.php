<?php

use Jokoli\Payment\Enums\PaymentStatus;
use Jokoli\Payment\Enums\SettlementStatus;

return [
    PaymentStatus::class => [
        PaymentStatus::Pending => "text-warning",
        PaymentStatus::Canceled => "text-error",
        PaymentStatus::Success => "text-success",
        PaymentStatus::Fail => "text-error",
    ],
    SettlementStatus::class => [
        SettlementStatus::Pending => "text-warning",
        SettlementStatus::Canceled => "text-dark",
        SettlementStatus::Settled => "text-success",
        SettlementStatus::Rejected => "text-error",
    ],
];
