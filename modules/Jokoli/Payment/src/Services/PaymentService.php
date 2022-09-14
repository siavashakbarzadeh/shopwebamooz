<?php

namespace Jokoli\Payment\Services;

use Jokoli\Payment\Gateways\Gateway;
use Jokoli\Payment\Repository\PaymentRepository;

class PaymentService
{
    public static function generate($price,$paymentable,$discount=null)
    {
        if (!$paymentable) return false;
        $percent = $paymentable->percent ?? 0;
        $seller_share = $percent ? ($price / 100) * $percent : 0;
        $gateway = resolve(Gateway::class);
        $invoice_id = $gateway->request($paymentable, $price);
        if (is_array($invoice_id)) {
            dd($invoice_id);
        }
        return resolve(PaymentRepository::class)->store($paymentable, [
            'buyer_id' => auth()->id(),
            'amount' => $price,
            'seller_percent' => $percent,
            'gateway' => $gateway->getName(),
            'invoice_id' => $invoice_id,
            'seller_share' => $seller_share,
            'site_share' => $price - $seller_share,
        ],[$paymentable->discount_id,optional($discount)->id]);
    }
}
