<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Jokoli\Course\Enums\LessonConfirmationStatus;
use Jokoli\Course\Enums\LessonStatus;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('course_id');
            $table->foreignId('media_id');
            $table->foreignId('season_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->string('duration')->nullable();
            $table->float('priority')->nullable();
            $table->boolean('is_free')->default(false);
            $table->unsignedTinyInteger('status')->default(LessonStatus::Opened);
            $table->unsignedTinyInteger('confirmation_status')->default(LessonConfirmationStatus::Pending);
            $table->text('body')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('course_id')->references('id')->on('courses')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('media_id')->references('id')->on('media')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('season_id')->references('id')->on('seasons')
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
        Schema::dropIfExists('lessons');
    }
}
