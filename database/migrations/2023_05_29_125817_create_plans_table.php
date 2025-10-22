<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administration.plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('period_type');
            $table->integer('period');
            $table->integer('order_quantity');
            $table->bigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on("public.statuses")->onDelete('cascade');
            $table->float('price', 8, 2)->nullable();
            $table->string('paypal_id')->nullable();
            $table->string('paypal_product_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
};
