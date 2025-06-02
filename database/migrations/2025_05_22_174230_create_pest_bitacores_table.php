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
        Schema::create("modules.pest_bitacores", function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pest_id');
            $table->foreign('pest_id')->references('id')->on("modules.pests")->onDelete('restrict');
            $table->bigInteger('control_of_rodent_id');
            $table->foreign('control_of_rodent_id')->references('id')->on("modules.control_of_rodents")->onDelete('cascade');
            $table->string('quantity');
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
        Schema::dropIfExists('modules.pest_bitacores');
    }
};
