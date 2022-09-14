<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Jokoli\Media\Models\Media;
use Jokoli\Ticket\Database\Factories\TicketReplyFactory;
use Jokoli\Ticket\Models\Ticket;
use Jokoli\User\Models\User;

/**
 * TicketReply
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply withTickerUserId()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply query()
 *
 */
class TicketReply extends Model
{
    use HasFactory;

    const Factory = TicketReplyFactory::class;

    protected $guarded = [];

    // region Relationships

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    // endregion

    // region Methods

    public function isAnswer()
    {
        return $this->ticket_user_id != $this->user_id;
    }

    public function downloadLink()
    {
        return $this->media_id ? URL::temporarySignedRoute('media.download', now()->addDay(), ['media' => $this->media_id]) : null;
    }

    // endregion

    public function scopeWithTickerUserId(Builder $builder)
    {
        $builder->withAggregate('ticket', 'user_id');
    }
}
