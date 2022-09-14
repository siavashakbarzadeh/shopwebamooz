<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('ticket_id');
            $table->foreignId('media_id')->nullable();
            $table->text('body');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('ticket_id')->references('id')->on('tickets')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('media_id')->references('id')->on('media')
                ->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_replies');
    }
}
