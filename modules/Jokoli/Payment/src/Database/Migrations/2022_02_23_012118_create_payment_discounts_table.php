<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_discounts', function (Blueprint $table) {
            $table->foreignId('payment_id');
            $table->foreignId('discount_id');
            $table->primary(['payment_id', 'discount_id']);

            $table->foreign('payment_id')->references('id')->on('payments')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('discount_id')->references('id')->on('discounts')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_discounts');
    }
}
