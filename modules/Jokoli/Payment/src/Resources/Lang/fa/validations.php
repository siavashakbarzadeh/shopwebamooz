<?php

return [
    'settlements' => [
        'amount' => [
            'max' => ":attribute درخواست نمیتواند بیشتر از موجودی قابل برداشت باشد",
            'min' => ":attribute نباید کمتر از :price باشد.",
        ],
    ],
    'attributes' => [
        'amount' => "مبلغ",
        'status' => "وضعیت درخواست تسویه",
        'from' => [
            'name' => "نام صاحب حساب",
            'card_number' => "شماره کارت",
        ],
        'to' => [
            'name' => "نام صاحب حساب گیرنده",
            'card_number' => "شماره کارت بانکی گیرنده",
        ],
    ],
];
