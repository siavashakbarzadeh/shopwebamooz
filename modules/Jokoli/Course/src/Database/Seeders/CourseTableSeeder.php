<?php

namespace Jokoli\Course\Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Models\Course;
use Jokoli\Media\Enums\MediaType;
use Jokoli\Media\Models\Media;
use Jokoli\Media\Services\ImageFileService;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Models\User;

class CourseTableSeeder extends Seeder
{
    public function run()
    {
        $courses = json_decode(file_get_contents(storage_path('app\courses.json')));
        foreach ($courses as $course) {
            $image = file_get_contents($course->image);
            $original = substr($course->image, strrpos($course->image, '/') + 1);
            $extension = str_after($original, '.');
            $filename = uniqid() . auth()->id() . time();
            Storage::disk('public')->put($filename . '.' . $extension, $image);
            $files = ImageFileService::resize(Storage::disk('public'), $filename, $extension);
            $faker = Factory::create('fa_IR');
            Course::query()->create([
                'category_id' => 2,
                'banner_id' => Media::query()->create([
                    'files' => $files,
                    'user_id'=>1,
                    'type' => MediaType::Image,
                    'disk' => 'public',
                    'filename' => $original,
                ])->id,
                'teacher_id' => User::permission(Permissions::Teach)->inRandomOrder()->first()->id,
                'title' => $course->title,
                'slug' => Str::slug($course->slug),
                'price' => $faker->randomElement([100000,200000,250000,300000,500000]),
                'percent' => rand(1, 100),
                'type' => CourseType::Cash,
                'status' => CourseStatus::Completed,
                'confirmation_status' => CourseConfirmationStatus::Pending,
                'body' =>$faker->realText,
            ]);
        }
    }
}
