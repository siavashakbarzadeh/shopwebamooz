<?php

namespace Jokoli\Course\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Jokoli\Category\Models\Category;
use Jokoli\Course\Database\Factories\CourseFactory;
use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Repository\CourseRepository;
use Jokoli\Discount\Models\Discount;
use Jokoli\Media\Models\Media;
use Jokoli\Media\Repository\MediaRepository;
use Jokoli\Media\Services\MediaFileService;
use Jokoli\Payment\Models\Payment;
use Jokoli\Ticket\Models\Ticket;
use Jokoli\User\Models\User;

/**
 * Course
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Course withDiscount()
 * @method static \Illuminate\Database\Eloquent\Builder|Course withSeasons()
 * @method static \Illuminate\Database\Eloquent\Builder|Course duration()
 * @method static \Illuminate\Database\Eloquent\Builder|Course accepted()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 *
 */
class Course extends Model
{
    use HasFactory;

    const Factory = CourseFactory::class;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::updated(function (Course $course) {
            if ($course->wasChanged('banner_id') && $media = resolve(MediaRepository::class)->findOrFailById($course->getOriginal('banner_id')))
                $media->delete();
        });
    }

    // region Relationships

    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function seasons()
    {
        return $this->hasMany(Season::class, 'course_id')->orderBy('priority');
    }

    public function latest_season()
    {
        return $this->hasOne(Season::class, 'course_id')->latestOfMany();
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id')->orderBy('priority');
    }

    public function accepted_lessons()
    {
        return $this->lessons()->accepted();
    }

    public function latest_lesson()
    {
        return $this->hasOne(Lesson::class, 'course_id')->latestOfMany();
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_students', 'course_id', 'user_id')
            ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function banner()
    {
        return $this->belongsTo(Media::class, 'banner_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'id');
    }

    // endregion

    // region Mutators

    public function getStatusFaAttribute()
    {
        return CourseStatus::fromValue(intval($this->status))->description;
    }

    public function getConfirmationStatusFaAttribute()
    {
        return CourseConfirmationStatus::fromValue(intval($this->confirmation_status))->description;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price);
    }

    public function getFormattedDurationAttribute()
    {
        return sprintf("%02d:%02d:%02d", floor($this->duration / 60), $this->duration % 60, 0);
    }

    // endregion

    // region Methods

    public function downloadLinks()
    {
        $links = [];
        foreach ($this->accepted_lessons as $lesson) {
            $links[] = $lesson->downloadLink();
        }
        return $links;
    }

    public function hasStudent($user_id)
    {
        return resolve(CourseRepository::class)->hasStudent($this, $user_id);
    }

    public function getConfirmationStatusCssClass()
    {
        return optional(CourseConfirmationStatus::coerce(intval($this->confirmation_status)))->getCssClass();
    }

    public function path($parameters = array())
    {
        return route('single-course', array_merge([$this->id, $this->slug], $parameters));
    }

    public function shortPath()
    {
        return route('single-course', $this->id);
    }

    public function getDiscountPercent()
    {
        return $this->discount_id ? $this->discount->percent : 0;
    }

    public function getDiscountAmount()
    {
        return $this->getDiscountPercent() ? ceil(($this->price / 100) * $this->getDiscountPercent()) : 0;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getFromattedPrice()
    {
        return number_format($this->price);
    }

    public function getFormattedDiscountAmount()
    {
        return number_format($this->getDiscountAmount());
    }

    public function getFinalPrice()
    {
        return $this->isFree() ? 0 : ceil($this->price - $this->getDiscountAmount());
    }

    public function getFormattedFinalPrice()
    {
        return number_format($this->getFinalPrice());
    }

    public function isFree()
    {
        return $this->type == CourseType::Free;
    }

    public function isCash()
    {
        return $this->type == CourseType::Cash;
    }

    // endregion

    // region Scopes

    public function scopeAccepted(Builder $builder)
    {
        $builder->where('confirmation_status', CourseConfirmationStatus::Accepted);
    }

    public function scopeDuration(Builder $builder)
    {
        $builder->withSum('accepted_lessons AS duration', 'duration');
    }

    public function scopeWithSeasons(Builder $builder)
    {
        $builder->with(['seasons' => function (HasMany $hasMany) {
            $hasMany->with('accepted_lessons')->has('accepted_lessons');
        }]);
    }

    public function scopeWithDiscount(Builder $builder)
    {
        $builder->addSelect([
            'discount_id' => Discount::query()
                ->select('id')
                ->where(function (Builder $builder) {
                    $builder->whereNotExists(function ($query) {
                        $query->from('discountables')
                            ->whereColumn('discountables.discount_id', 'discounts.id');
                    })->orWhereExists(function ($query) {
                        $query->from('discountables')
                            ->whereColumn('discountables.discount_id', 'discounts.id')
                            ->where('discountables.discountable_type', $this->getMorphClass())
                            ->whereColumn('discountables.discountable_id', 'courses.id');
                    });
                })->expireAtAfterNowOrNull()
                ->doesntHaveCode()
                ->usageLimitationGreaterThanZeroOrNull()
                ->orderByDesc('percent')
                ->limit(1),
        ])->with('discount');
    }

    // endregion
}
