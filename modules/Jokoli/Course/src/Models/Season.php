<?php

namespace Jokoli\Course\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jokoli\Course\Database\Factories\SeasonFactory;
use Jokoli\Course\Enums\SeasonConfirmationStatus;
use Jokoli\Course\Enums\SeasonStatus;
use Jokoli\User\Models\User;

class Season extends Model
{
    use HasFactory;

    const Factory = SeasonFactory::class;

    protected $guarded = [];

    // region Relationships

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'season_id')->orderBy('priority','ASC');
    }

    public function accepted_lessons()
    {
        return $this->lessons()->accepted();
    }

    // endregion

    // region Mutators

    public function getConfirmationStatusFaAttribute()
    {
        return SeasonConfirmationStatus::getDescription(intval($this->confirmation_status));
    }

    public function getStatusFaAttribute()
    {
        return SeasonStatus::getDescription(intval($this->status));
    }

    // endregion

    // region Methods

    public function getConfirmationStatusCssClass()
    {
        return optional(SeasonConfirmationStatus::coerce(intval($this->confirmation_status)))->getCssClass();
    }

    public function getStatusCssClass()
    {
        return optional(SeasonStatus::coerce(intval($this->status)))->getCssClass();
    }

    // endregion

    // region Scopes

    public function scopePending(Builder $builder)
    {
        $builder->where('confirmation_status', SeasonConfirmationStatus::Pending);
    }

    public function scopeAccepted(Builder $builder)
    {
        $builder->where('confirmation_status', SeasonConfirmationStatus::Accepted);
    }

    public function scopeRejected(Builder $builder)
    {
        $builder->where('confirmation_status', SeasonConfirmationStatus::Rejected);
    }

    public function scopeSort(Builder $builder)
    {
        $builder->orderBy('priority');
    }

    // endregion
}
