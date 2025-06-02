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
        Schema::create("modules.infestation_grades", function (Blueprint $table) {
            $table->id();
            $table->string('german_cockroaches')->nullable();
            $table->string('flies')->nullable();
            $table->string('bees')->nullable();
            $table->string('termites')->nullable();
            $table->string('fleas')->nullable();
            $table->string('moths')->nullable();
            $table->string('weevils')->nullable();
            $table->string('american_cockroaches')->nullable();
            $table->string('ants')->nullable();
            $table->string('spiders')->nullable();
            $table->string('rodents')->nullable();
            $table->string('fire_ants')->nullable();
            $table->string('stilt_walkers')->nullable();
            $table->string('others')->nullable();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on("modules.orders")->onDelete('cascade');
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
        Schema::dropIfExists('modules.infestation_grades');
    }
};
