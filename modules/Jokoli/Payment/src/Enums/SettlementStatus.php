<?php

namespace Jokoli\Payment\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @method static static Pending()
 * @method static static Settled()
 * @method static static Rejected()
 * @method static static Canceled()
 */
class SettlementStatus extends Enum implements LocalizedEnum
{
    const Pending = 0;
    const Settled = 1;
    const Rejected = 2;
    const Canceled = 3;

    public static function getLocalizationKey(): string
    {
        return 'Payment::enums.' . static::class;
    }

    public function getCssClass()
    {
        return trans('Payment::classes.' . static::class . '.' . $this->value);
    }

    public function hasStatus($status = null): bool
    {
        return Str::is($this->key, ucfirst($status));
    }

    public function getKeyLower(): string
    {
        return strtolower($this->key);
    }

    public function getParameters(array $parameters = []): array
    {
        return Arr::set($parameters, 'status', $this->getKeyLower());
    }
}
