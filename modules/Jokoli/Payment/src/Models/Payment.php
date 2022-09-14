<?php

namespace Jokoli\Payment\Models;

use Hekmatinasser\Verta\Laravel\JalaliValidator;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jokoli\Course\Models\Course;
use Jokoli\Discount\Models\Discount;
use Jokoli\Payment\Enums\PaymentStatus;
use Jokoli\User\Models\User;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * Payment
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Payment success()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment filterEmail($email)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment filterAmount($amount)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment filterInvoiceId($invoice_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment filterFrom($from)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment filterTo($to)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 *
 */
class Payment extends Model
{
    use HasFactory, HasRelationships;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    // region Relationships

    public function paymentable()
    {
        return $this->morphTo('paymentable');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->hasOneDeep(User::class, [Course::class], ['id', 'id'], ['paymentable_id', 'teacher_id']);
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'payment_discounts');
    }

    // endregion

    // region Methods

    public function getStatusCssClass()
    {
        return PaymentStatus::fromValue($this->status)->getCssClass();
    }

    // endregion

    // region Mutators

    public function getStatusFaAttribute()
    {
        return PaymentStatus::getDescription($this->status);
    }

    // endregion

    // region Scopes

    public function scopePending(Builder $builder)
    {
        $builder->where('status', PaymentStatus::Pending);
    }

    public function scopeSuccess(Builder $builder)
    {
        $builder->where('status', PaymentStatus::Success);
    }

    public function scopeCanceled(Builder $builder)
    {
        $builder->where('status', PaymentStatus::Canceled);
    }

    public function scopeFail(Builder $builder)
    {
        $builder->where('status', PaymentStatus::Fail);
    }

    public function scopeFilterEmail(Builder $builder, $email = null)
    {
        $builder->when($email, function (Builder $builder) use ($email) {
            $builder->whereHas('buyer', function (Builder $builder) use ($email) {
                $builder->where('email', 'LIKE', '%' . $email . '%');
            });
        });
    }

    public function scopeFilterAmount(Builder $builder, $amount = null)
    {
        $builder->when($amount, function (Builder $builder) use ($amount) {
            $builder->where('amount', str_replace(',', '', $amount));
        });
    }

    public function scopeFilterInvoiceId(Builder $builder, $invoice_id = null)
    {
        $builder->when($invoice_id, function (Builder $builder) use ($invoice_id) {
            $builder->where('invoice_id', 'LIKE', '%' . $invoice_id . '%');
        });
    }

    public function scopeFilterFrom(Builder $builder, $from = null)
    {
        $builder->when($from && validateJalaliDate($from), function (Builder $builder) use ($from) {
            $builder->whereDate('created_at', '>=', Verta::parse($from)->datetime());
        });
    }

    public function scopeFilterTo(Builder $builder, $to = null)
    {
        $builder->when($to && validateJalaliDate($to), function (Builder $builder) use ($to) {
            $builder->whereDate('created_at', '<=', Verta::parse($to)->datetime());
        });
    }

    // endregion
}
