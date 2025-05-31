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
        Schema::create("modules.fumigations", function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("modules.orders")->onDelete('cascade');
            $table->bigInteger('aplication_id');
            $table->foreign('aplication_id')->references('id')->on("modules.aplications")->onDelete('restrict');
            $table->bigInteger('aplication_place_id');
            $table->foreign('aplication_place_id')->references('id')->on("modules.aplication_places")->onDelete('restrict');
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
        Schema::dropIfExists('modules.fumigations');
    }
};
