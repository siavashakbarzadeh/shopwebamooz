<?php

namespace Jokoli\Payment\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Pending()
 * @method static static Canceled()
 * @method static static Success()
 * @method static static Fail()
 */
class PaymentStatus extends Enum implements LocalizedEnum
{
    const Pending = 0;
    const Canceled = 1;
    const Success = 2;
    const Fail = 3;

    public static function getLocalizationKey(): string
    {
        return 'Payment::enums.' . static::class;
    }

    public function getCssClass()
    {
        return trans('Payment::classes.' . static::class . '.' . $this->value);
    }
}
