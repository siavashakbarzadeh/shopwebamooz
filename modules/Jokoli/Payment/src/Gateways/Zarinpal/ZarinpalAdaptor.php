<?php

namespace Jokoli\Payment\Gateways\Zarinpal;

use Jokoli\Payment\Contracts\GatewayContract;
use Jokoli\Payment\Models\Payment;
use Jokoli\Payment\Repository\PaymentRepository;

class ZarinpalAdaptor implements GatewayContract
{

    private $client;
    private $url;

    public function request($paymentable, $amount)
    {
        $this->client = new Zarinpal();
        $callback = route('payments.callback');
        $result = $this->client->request("xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx", $amount, "خرید ".$paymentable->title." - ".env('APP_NAME'), "", "", $callback, true);
        if (isset($result["Status"]) && $result["Status"] == 100) {
            $this->url = $result['StartPay'];
            return $result['Authority'];
        } else {
            return [
                'status' => $result["Status"],
                'message' => $result["Message"],
            ];
        }
    }

    public function verify($payment)
    {
        $this->client = new Zarinpal();
        $result = $this->client->verify("xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx", $payment->amount, true);
        return isset($result["Status"]) && $result["Status"] == 100 ? $result["RefID"] : [
            'status' => $result["Status"],
            'message' => $result["Message"],
        ];
    }


    public function redirect()
    {
        $this->client->redirect($this->url);
    }

    public function getName()
    {
        return "zarinpal";
    }

}
