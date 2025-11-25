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
        Schema::create('modules.lamp_captures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pest_id');
            $table->foreign('pest_id')->references('id')->on("modules.pests")->onDelete('restrict');
            $table->bigInteger('lamp_id');
            $table->foreign('lamp_id')->references('id')->on("modules.lamps")->onDelete('cascade');
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
        Schema::dropIfExists('modules.lamp_captures');
    }
};
