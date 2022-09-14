<?php

namespace Jokoli\Payment\Models;

use BenSampo\Enum\Exceptions\InvalidEnumKeyException;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jokoli\Payment\Enums\SettlementStatus;
use Jokoli\User\Models\User;

/**
 * Settlement
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Settlement pending()
 * @method static \Illuminate\Database\Eloquent\Builder|Settlement filterStatus($status = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Settlement filterCard($card = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Settlement filterTransactionId($transaction_id = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Settlement filterDate($date = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Settlement filterEmail($email = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Settlement filterName($name = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Settlement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Settlement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Settlement query()
 *
 */
class Settlement extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'from' => 'array',
        'to' => 'array',
        'settled_at' => 'datetime',
    ];

    // region Relationships

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // endregion

    // region Methods

    public function getStatusCssClass()
    {
        return SettlementStatus::fromValue($this->status)->getCssClass();
    }

    // endregion

    // region Mutators

    public function getStatusFaAttribute()
    {
        return SettlementStatus::getDescription($this->status);
    }

    // endregion

    // region Scopes

    public function scopePending(Builder $builder)
    {
        $builder->where('status', SettlementStatus::Pending);
    }

    public function scopeFilterStatus(Builder $builder, $status = null)
    {
        $builder->when($status && in_array(ucfirst($status), SettlementStatus::getKeys([SettlementStatus::Settled, SettlementStatus::Settled])), function (Builder $builder) use ($status) {
            $builder->where('status', SettlementStatus::coerce(ucfirst($status))->value);
        });
    }

    public function scopeFilterCard(Builder $builder, $card = null)
    {
        $builder->when($card, function (Builder $builder) use ($card) {
            $builder->where('from->card_number', 'LIKE', '%' . $card . '%');
        });
    }

    public function scopeFilterTransactionId(Builder $builder, $transaction_id = null)
    {
        $builder->when($transaction_id, function (Builder $builder) use ($transaction_id) {
            $builder->where('transaction_id', 'LIKE', '%' . $transaction_id . '%');
        });
    }

    public function scopeFilterDate(Builder $builder, string $date = null)
    {
        $builder->when($date && validateJalaliDate($date), function (Builder $builder) use ($date) {
            $builder->whereDate('created_at', Verta::parse($date)->datetime());
        });
    }

    public function scopeFilterEmail(Builder $builder, string $email = null)
    {
        $builder->when($email, function (Builder $builder) use ($email) {
            $builder->whereHas('user', function (Builder $builder) use ($email) {
                $builder->where('email', 'LIKE', '%' . $email . '%');
            });
        });
    }

    public function scopeFilterName(Builder $builder, string $name = null)
    {
        $builder->when($name, function (Builder $builder) use ($name) {
            $builder->whereHas('user', function (Builder $builder) use ($name) {
                $builder->where('name', 'LIKE', '%' . $name . '%');
            });
        });
    }

    // endregion

}
