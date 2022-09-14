<?php

namespace Jokoli\Discount\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jokoli\Course\Models\Course;
use Jokoli\Discount\Database\Factories\DiscountFactory;

/**
 * Discount
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Discount doesntHaveCode()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount expireAtAfterNowOrNull()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount usageLimitationGreaterThanZeroOrNull()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount query()
 *
 */
class Discount extends Model
{
    use HasFactory;

    const Factory = DiscountFactory::class;

    protected $guarded = [];

    protected $casts = [
        'expire_at' => 'datetime'
    ];

    public function courses()
    {
        return $this->morphedByMany(Course::class, 'discountable');
    }

    // region Scopes

    public function scopeUsageLimitationGreaterThanZeroOrNull(Builder $builder)
    {
        $builder->where(function (Builder $builder) {
            $builder->whereNull('usage_limitation')->orWhere('usage_limitation', '>', 0);
        });
    }

    public function scopeExpireAtAfterNowOrNull(Builder $builder)
    {
        $builder->where(function (Builder $builder) {
            $builder->whereNull('expire_at')->orWhere('expire_at', '>=', now());
        });
    }

    public function scopeDoesntHaveCode(Builder $builder)
    {
        $builder->whereNull('code');
    }

    // endregion

}
