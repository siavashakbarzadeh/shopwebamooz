<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->unsignedTinyInteger('percent');
            $table->string('code')->nullable();
            $table->unsignedBigInteger('usage_limitation')->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->string('link')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('uses')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->cascadeOnUpdate()->nullOnDelete();
        });
        Schema::create('discountables', function (Blueprint $table) {
            $table->foreignId('discount_id');
            $table->morphs('discountable');
            $table->primary(['discount_id','discountable_id','discountable_type'],'discountables_key');

            $table->foreign('discount_id')->references('id')->on('discounts')
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
        Schema::dropIfExists('discountables');
        Schema::dropIfExists('discounts');
    }
}
