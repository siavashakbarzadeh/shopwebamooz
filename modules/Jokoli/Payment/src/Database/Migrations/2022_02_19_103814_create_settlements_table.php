<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Jokoli\Payment\Enums\SettlementStatus;

class CreateSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->json('from')->nullable();
            $table->json('to')->nullable();
            $table->string('amount');
            $table->unsignedTinyInteger('status')->default(SettlementStatus::Pending);
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('settlements');
    }
}
