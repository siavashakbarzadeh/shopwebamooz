<?php

namespace Jokoli\Payment\Contracts;

use Jokoli\Payment\Models\Payment;

interface GatewayContract
{
    public function request($paymentable,$amount);

    public function verify($payment);

    public function redirect();

    public function getName();
}
