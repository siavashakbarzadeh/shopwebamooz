<?php

use Jokoli\Payment\Enums\PaymentStatus;
use Jokoli\Payment\Enums\SettlementStatus;

return [
    PaymentStatus::class => [
        PaymentStatus::Pending => "در انتظار پرداخت",
        PaymentStatus::Canceled => "لغو شده",
        PaymentStatus::Success => "پرداخت موفق",
        PaymentStatus::Fail => "خطا در پرداخت",
    ],
    SettlementStatus::class => [
        SettlementStatus::Pending => "در انتظار تسویه",
        SettlementStatus::Canceled => "لغو شده",
        SettlementStatus::Settled => "تسویه شده",
        SettlementStatus::Rejected => "رد شده",
    ],
];
