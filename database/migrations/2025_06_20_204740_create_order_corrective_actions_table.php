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
        Schema::create('modules.order_corrective_actions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('control_of_rodent_id')->nullable();
            $table->foreign('control_of_rodent_id')->references('id')->on("modules.control_of_rodents")->onDelete('cascade');
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
        Schema::dropIfExists('modules.order_corrective_actions');
    }
};
