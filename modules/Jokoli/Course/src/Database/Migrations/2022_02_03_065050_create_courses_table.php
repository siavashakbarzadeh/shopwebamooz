<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Jokoli\Course\Enums\CourseConfirmationStatus;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id');
            $table->foreignId('category_id')->nullable();
            $table->foreignId('banner_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->float('priority')->nullable();
            $table->string('price');
            $table->string('percent');
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('confirmation_status')->default(CourseConfirmationStatus::Pending);
            $table->text('body')->nullable();
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('users')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('category_id')->references('id')->on('categories')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('banner_id')->references('id')->on('media')
                ->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
