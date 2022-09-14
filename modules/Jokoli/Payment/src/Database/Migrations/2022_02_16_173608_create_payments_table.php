<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Jokoli\Payment\Enums\PaymentStatus;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->nullable();
            $table->morphs('paymentable');
            $table->string('amount');
            $table->string('invoice_id');
            $table->string('gateway');
            $table->unsignedTinyInteger('status')->default(PaymentStatus::Pending);
            $table->unsignedTinyInteger('seller_percent');
            $table->string('seller_share');
            $table->string('site_share');
            $table->timestamps();

            $table->foreign('buyer_id')->references('id')->on('users')
                ->cascadeOnDelete()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
