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
        Schema::create("modules.control_of_legionella", function (Blueprint $table) {
            $table->id();

            $table->string('code');

            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("modules.orders")->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('location_id');
            $table->foreign('location_id')->references('id')->on("modules.locations")->onUpdate('cascade')->onDelete('cascade');
            $table->date('last_treatment_date')->nullable();
            $table->date('next_treatment_date')->nullable();
            $table->string('inspection_result')->nullable();
            $table->boolean('sample_required')->default(false);
            $table->float('water_temperature')->nullable();
            $table->float('residual_chlorine_level')->nullable();
            $table->text('observation')->nullable();
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
        Schema::dropIfExists('modules.control_of_legionella');
    }
};
