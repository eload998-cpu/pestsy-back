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
        Schema::create('modules.trap_corrective_actions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trap_id')->nullable();
            $table->foreign('trap_id')->references('id')->on("modules.traps")->onDelete('cascade');
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
        Schema::dropIfExists('modules.trap_corrective_actions');
    }
};
