<?php

namespace Jokoli\Ticket\Models;

use App\Models\TicketReply;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jokoli\Ticket\Database\Factories\TicketFactory;
use Jokoli\Ticket\Enums\TicketAnswerStatus;
use Jokoli\Ticket\Enums\TicketStatus;
use Jokoli\User\Models\User;

/**
 * Ticket
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket query()
 *
 */
class Ticket extends Model
{
    use HasFactory;

    const Factory = TicketFactory::class;

    protected $guarded = [];

    protected $casts = [
        'latest_reply_created_at' => 'datetime',
        'status' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (Ticket $ticket) {
            foreach ($ticket->replies()->with('media')->get()->pluck('media')->filter() as $media) {
                $media->delete();
            }
        });
    }

    // region Relationships

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class, 'ticket_id')->withTickerUserId();
    }

    public function latest_reply()
    {
        return $this->hasOne(TicketReply::class, 'ticket_id')->latestOfMany();
    }

    // endregion

    // region Methods

    public function isClosed()
    {
        return TicketStatus::Close()->is($this->getAttribute('status'));
    }

    public function getStatusCssClass()
    {
        return optional(TicketStatus::coerce($this->status))->getCssClass();
    }

    public function getAnswerStatus()
    {
        return $this->latest_reply_user_id == $this->user_id ? trans('Ticket::translations.not_answered') : trans('Ticket::translations.answered');
    }

    public function getAnswerStatusCssClass()
    {
        return $this->latest_reply_user_id == $this->user_id ? trans('Ticket::classes.not_answered') : trans('Ticket::classes.answered');
    }

    // endregion

    // region Mutators

    public function getStatusFaAttribute()
    {
        return TicketStatus::fromValue($this->getAttribute('status'))->description;
    }

    // endregion

    // region Scopes

    public function scopeFilterAnswerStatus(Builder $builder, $status = null)
    {
        $builder->when(TicketAnswerStatus::hasKey(ucfirst($status)), function (Builder $builder) use ($status) {
            $builder->whereHas('latest_reply', function (Builder $builder) use ($status) {
                $builder->when(TicketAnswerStatus::coerce(ucfirst($status))->is(TicketAnswerStatus::Answered), function (Builder $builder) {
                    $builder->whereColumn('tickets.user_id', '!=', 'ticket_replies.user_id');
                })->when(TicketAnswerStatus::coerce(ucfirst($status))->is(TicketAnswerStatus::Answered), function (Builder $builder) {
                    $builder->whereColumn('tickets.user_id', '=', 'ticket_replies.user_id');
                });
            });
        });
    }

    public function scopeFilterName(Builder $builder, $name = null)
    {
        $builder->when($name, function (Builder $builder) use ($name) {
            $builder->whereHas('user', function (Builder $builder) use ($name) {
                $builder->where('name', 'LIKE', '%' . $name . '%');
            });
        });
    }

    public function scopeFilterEmail(Builder $builder, $email = null)
    {
        $builder->when($email, function (Builder $builder) use ($email) {
            $builder->whereHas('user', function (Builder $builder) use ($email) {
                $builder->where('email', 'LIKE', '%' . $email . '%');
            });
        });
    }

    public function scopeFilterDate(Builder $builder, $date = null)
    {
        $builder->when($date && validateJalaliDate($date,'Y/n/j'), function (Builder $builder) use ($date) {
            $builder->whereDate('created_at', Verta::parseFormat('Y/n/j',$date)->datetime());
        });
    }

    // endregion

}
