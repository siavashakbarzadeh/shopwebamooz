<?php

namespace Jokoli\Course\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Jokoli\Course\Database\Factories\LessonFactory;
use Jokoli\Course\Enums\LessonConfirmationStatus;
use Jokoli\Course\Enums\LessonStatus;
use Jokoli\Course\Enums\SeasonConfirmationStatus;
use Jokoli\Course\Enums\SeasonStatus;
use Jokoli\Media\Models\Media;
use Jokoli\Media\Repository\MediaRepository;
use Jokoli\Media\Services\MediaFileService;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Models\User;

class Lesson extends Model
{
    use HasFactory;

    const Factory = LessonFactory::class;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::updated(function (Lesson $lesson) {
            if ($lesson->wasChanged(['media_id']) && $media = resolve(MediaRepository::class)->findById($lesson->getOriginal('media_id')))
                $media->delete();
        });
        static::deleted(function (Lesson $lesson) {
            $lesson->media->delete();
        });
    }

    // region Relationship

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id')->withDefault(['title' => "بدون سرفصل"]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    // endregion

    // region Scopes

    public function scopePending(Builder $builder)
    {
        $builder->where('confirmation_status', LessonConfirmationStatus::Pending);
    }

    public function scopeAccepted(Builder $builder)
    {
        $builder->where('confirmation_status', LessonConfirmationStatus::Accepted);
    }

    public function scopeRejected(Builder $builder)
    {
        $builder->where('confirmation_status', LessonConfirmationStatus::Rejected);
    }

    public function scopeHasAccess(Builder $builder)
    {
        $builder->when(!optional(auth()->user())->can(Permissions::ManageCourses), function (Builder $builder) {
            $builder->where(function (Builder $builder) {
                $builder->whereHas('course.students', function (Builder $builder) {
                    $builder->where('id', auth()->id());
                })->orWhere('is_free', true);
            });
        });
    }

    // endregion

    // region Mutators

    public function getConfirmationStatusFaAttribute()
    {
        return LessonConfirmationStatus::getDescription($this->confirmation_status);
    }

    public function getStatusFaAttribute()
    {
        return LessonStatus::getDescription($this->status);
    }

    public function getFormattedDurationAttribute()
    {
        return sprintf("%02d:%02d:%02d", floor($this->duration / 60), $this->duration % 60, 0);
    }

    // endregion

    // region Methods

    public function getConfirmationStatusCssClass()
    {
        return optional(LessonConfirmationStatus::coerce($this->confirmation_status))->getCssClass();
    }

    public function getStatusCssClass()
    {
        return optional(LessonStatus::coerce($this->status))->getCssClass();
    }

    public function path()
    {
        return $this->course->path(['lesson' => $this->id]);
    }

    public function downloadLink()
    {
        return $this->media_id ? URL::temporarySignedRoute('media.download', now()->addDay(), ['media' => $this->media_id]) : null;
    }

    // endregion

}
