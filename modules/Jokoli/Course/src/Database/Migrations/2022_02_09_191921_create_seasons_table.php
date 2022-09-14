<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Jokoli\Course\Enums\SeasonConfirmationStatus;
use Jokoli\Course\Enums\SeasonStatus;

class CreateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('course_id');
            $table->string('title');
            $table->float('priority')->nullable();
            $table->unsignedSmallInteger('status')->default(SeasonStatus::Opened);
            $table->unsignedSmallInteger('confirmation_status')->default(SeasonConfirmationStatus::Pending);
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
        Schema::dropIfExists('seasons');
    }
}
