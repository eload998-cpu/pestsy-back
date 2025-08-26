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
        Schema::create('modules.legionella_control_corrective_actions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('legionella_control_id')->nullable();
            $table->foreign('legionella_control_id')->references('id')->on("modules.control_of_xylophages")->onDelete('cascade');
            $table->bigInteger('corrective_action_id')->nullable();
            $table->foreign('corrective_action_id')->references('id')->on("modules.corrective_actions")->onDelete('cascade');
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
        Schema::dropIfExists('modules.legionella_control_corrective_actions');
    }
};
