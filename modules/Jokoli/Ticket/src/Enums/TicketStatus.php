<?php

namespace Jokoli\Ticket\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Pending()
 * @method static static Open()
 * @method static static Close()
 */
class TicketStatus extends Enum implements LocalizedEnum
{
    const Pending = 0;
    const Open = 1;
    const Close = 2;

    public static function getLocalizationKey(): string
    {
        return 'Ticket::enums.' . static::class;
    }

    public function getCssClass()
    {
        return trans('Ticket::classes.' . static::class . '.' . $this->value);
    }
}
