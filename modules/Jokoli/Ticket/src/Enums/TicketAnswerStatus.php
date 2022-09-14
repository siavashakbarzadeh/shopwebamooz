<?php

namespace Jokoli\Ticket\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @method static static Answered()
 * @method static static New()
 * @method static static Close()
 */
class TicketAnswerStatus extends Enum implements LocalizedEnum
{
    const Answered = 0;
    const New = 1;

    public static function getLocalizationKey(): string
    {
        return 'Ticket::enums.' . static::class;
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
