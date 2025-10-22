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
        Schema::create("modules.control_of_rodents", function (Blueprint $table) {
            $table->id();
            $table->bigInteger('device_id');
            $table->foreign('device_id')->references('id')->on("modules.devices")->onDelete('restrict');
            $table->bigInteger('product_id');
            $table->foreign('product_id')->references('id')->on("modules.products")->onDelete('restrict');
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("modules.orders")->onDelete('cascade');
            $table->bigInteger('device_number');
            $table->bigInteger('location_id');
            $table->foreign('location_id')->references('id')->on("modules.locations")->onDelete('restrict');
            $table->string('bait_status');
            $table->bigInteger('dose');
            $table->string('activity');
            $table->text('observation');
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
        Schema::dropIfExists('modules.control_of_rodents');
    }
};
