<?php

namespace Jokoli\User\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Models\Season;
use Jokoli\Discount\Models\Discount;
use Jokoli\Media\Models\Media;
use Jokoli\Media\Repository\MediaRepository;
use Jokoli\Payment\Models\Payment;
use Jokoli\Payment\Models\Settlement;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Ticket\Models\Ticket;
use Jokoli\User\Database\Factories\UserFactory;
use Jokoli\User\Enums\UserStatus;
use Jokoli\User\Notifications\ResetPasswordRequestNotification;
use Jokoli\User\Notifications\VerifyMailNotification;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasRelationships;

    const Factory = UserFactory::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image_id',
        'name',
        'email',
        'mobile',
        'username',
        'headline',
        'bio',
        'ip',
        'telegram',
        'card_number',
        'iban',
        'balance',
        'email_verified_at',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::updated(function (User $user) {
            if ($user->getOriginal('image_id') && $user->getAttribute('image_id') != $user->getOriginal('image_id') && $media = resolve(MediaRepository::class)->findOrFailById($user->getOriginal('image_id')))
                $media->delete();
        });
        static::deleted(function (User $user) {
            $user->image()->delete();
        });
    }

    // region Mutators

    public function getThumbAttribute()
    {
        return $this->image ? $this->image->thumb : asset('panel/img/pro.jpg');
    }

    // endregion

    // region Relationships

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function settlements()
    {
        return $this->hasMany(Settlement::class, 'user_id');
    }

    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function accepted_courses()
    {
        return $this->hasMany(Course::class, 'teacher_id')->accepted();
    }

    public function students()
    {
        return $this->hasManyDeep(User::class, [Course::class, 'course_students'], ['teacher_id', 'course_id', 'id'], ['id', 'id', 'user_id']);
    }

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }

    public function purchases()
    {
        return $this->belongsToMany(Course::class, 'course_students', 'user_id', 'course_id')
            ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'buyer_id');
    }

    public function sales()
    {
        return $this->hasManyDeep(Payment::class, [Course::class], ['teacher_id', ['paymentable_type', 'paymentable_id']], ['id', null]);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class,'user_id');
    }

    // endregion

    // region Overrides

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyMailNotification());
    }

    // endregion

    // region Methods

    public function getStatusFaAttribute()
    {
        return UserStatus::fromValue(intval($this->status))->description;
    }

    public function getStatusCssClass()
    {
        return UserStatus::fromValue(intval($this->status))->getCssClass();
    }

    public function profilePath()
    {
        return $this->username ? route('tutor', $this->username) : null;
    }

    public function infoPath()
    {
        return route('users.info', $this->id);
    }


    // endregion

    // region Notifications

    public function sendRestPasswordRequestNotification()
    {
        $this->notify(new ResetPasswordRequestNotification());
    }

    // endregion

}
