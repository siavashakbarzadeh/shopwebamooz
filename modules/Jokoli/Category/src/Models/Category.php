<?php

namespace Jokoli\Category\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jokoli\Category\Database\Factories\CategoryFactory;
use Jokoli\Course\Models\Course;

class Category extends Model
{
    use HasFactory;

    const Factory = CategoryFactory::class;

    protected $guarded = [];

    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getParentTitleAttribute()
    {
        return $this->attributes['parent_title'] ?? trans('does not have');
    }

    public function path()
    {
        return route('categories.show', $this->slug);
    }

    public function scopeParents(Builder $builder)
    {
        $builder->whereNull('parent_id');
    }

}
