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
        Schema::create("modules.internal_conditions", function (Blueprint $table) {
            $table->id();
            $table->string('walls')->nullable();
            $table->string('floors')->nullable();
            $table->string('cleaning')->nullable();
            $table->string('windows')->nullable();
            $table->string('storage')->nullable();
            $table->string('space')->nullable();
            $table->string('evidences')->nullable();
            $table->string('roofs')->nullable();
            $table->string('sealings')->nullable();
            $table->string('closed_doors')->nullable();
            $table->string('pests_facilities')->nullable();
            $table->string('garbage_cans')->nullable();
            $table->string('equipment')->nullable();
            $table->string('ventilation')->nullable();
            $table->string('ducts')->nullable();
            $table->string('clean_walls')->nullable();
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
        Schema::dropIfExists('modules.internal_conditions');
    }
};
