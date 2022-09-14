<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CourseStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_students', function (Blueprint $table) {
            $table->foreignId('user_id');
            $table->foreignId('course_id');
            $table->primary(['user_id','course_id']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('course_id')->references('id')->on('courses')
                ->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_students');
    }
}
