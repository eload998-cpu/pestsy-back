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
        Schema::create("modules.control_of_xylophages", function (Blueprint $table) {
            $table->id();

            $table->bigInteger('affected_element_id');
            $table->foreign('affected_element_id')->references('id')->on("modules.affected_elements")->onDelete('cascade');

            $table->bigInteger('construction_type_id');
            $table->foreign('construction_type_id')->references('id')->on("modules.construction_types")->onDelete('cascade');

            $table->bigInteger('product_id');
            $table->foreign('product_id')->references('id')->on("modules.products")->onDelete('cascade');

            $table->bigInteger('pest_id');
            $table->foreign('pest_id')->references('id')->on("modules.pests")->onDelete('cascade');

            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("modules.orders")->onDelete('cascade');

            $table->string('infestation_level')->nullable();
            $table->date('treatment_date')->nullable();
            $table->date('next_treatment_date')->nullable();

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
        Schema::dropIfExists('control_of_xylophages');
    }
};
