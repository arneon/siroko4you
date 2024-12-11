<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->id();
            $table->string('cart_code', 50)->unique();
            $table->string('session_id');
            $table->json('items');
            $table->decimal('total_price', 20, 6)->default(0);
            $table->smallInteger('status')->index()->default(0)->comment('0 -> pendent, 1 -> in checkout, 2 -> complete');
            $table->bigInteger('customer_id')->unsigned()->nullable();
//            $table->foreign('customer_id')->references('id')->on('customers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_carts');
    }
};
