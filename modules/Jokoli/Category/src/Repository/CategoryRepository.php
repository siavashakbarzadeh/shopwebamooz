<?php

namespace Jokoli\Category\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Jokoli\Category\Http\Requests\CategoryRequest;
use Jokoli\Category\Models\Category;

class CategoryRepository
{
    private function query()
    {
        return Category::query();
    }

    public function findById($id)
    {
        return $this->query()->findOrFail($id);
    }

    public function getCategories()
    {
        return $this->query()->with('subCategories')->parents()->get();
    }

    public function store(CategoryRequest $request)
    {
        return Category::query()->create([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'parent_id' => $request->parent_id,
        ]);
    }

    public function update($category, CategoryRequest $request)
    {
        return $category->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'parent_id' => $request->parent_id,
        ]);
    }

    public function allExceptById($id)
    {
        return $this->query()->where('categories.id', '!=', $id)->get();
    }

    public function get()
    {
        return $this->model->get();
    }

    public function all($withParentName = false)
    {
        return $this->query()->when($withParentName, function (Builder $builder) {
            $builder->withAggregate('parent', 'title');
        })->get();
    }

    public function destroy($category)
    {
        $category->delete();
    }

}
