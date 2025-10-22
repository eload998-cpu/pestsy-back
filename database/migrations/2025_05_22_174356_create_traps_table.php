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
        Schema::create("modules.traps", function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("modules.orders")->onDelete('cascade');
            $table->integer('station_number');
            $table->bigInteger('device_id');
            $table->foreign('device_id')->references('id')->on("modules.devices")->onDelete('restrict');
            $table->string('pheromones');
            $table->bigInteger('product_id');
            $table->foreign('product_id')->references('id')->on("modules.products")->onDelete('restrict');
            $table->bigInteger('dose');
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
        Schema::dropIfExists('modules.traps');
    }
};
