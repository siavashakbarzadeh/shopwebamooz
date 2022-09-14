<?php

namespace Jokoli\Category\Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Jokoli\Category\Models\Category;

class CategoryTableSeeder extends Seeder
{
    public function run()
    {
        $categories = json_decode(file_get_contents(storage_path('app\categories-defined.json')));
        foreach ($categories as $category) {
            $parent=Category::factory()->state(['title' => $category->title, 'slug' => Str::slug($category->slug)])->create();
            foreach ($category->children as $child) {
                Category::factory()->state(['parent_id'=>$parent->id,'title' => $child->title, 'slug' => Str::slug($child->slug)])->create();
            }
        }
    }
}
