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
        Schema::create('modules.fumigation_safety_controls', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fumigation_id')->nullable();
            $table->foreign('fumigation_id')->references('id')->on("modules.fumigations")->onDelete('cascade');
            $table->bigInteger('safety_control_id')->nullable();
            $table->foreign('safety_control_id')->references('id')->on("modules.safety_controls")->onDelete('cascade');
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
        Schema::dropIfExists('modules.fumigation_safety_controls');
    }
};
