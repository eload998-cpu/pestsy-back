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
           Schema::create("modules.lamps", function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("modules.orders")->onDelete('cascade');
            $table->string('lamp_not_working');
            $table->integer('station_number');
            $table->string('rubbery_iron_changed');
            $table->string('fluorescent_change')->nullable();
            $table->string('observation')->nullable();
            $table->string('lamp_cleaning');
            $table->integer('quantity_replaced')->nullable();
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
        Schema::dropIfExists('modules.lamps');
    }
};
