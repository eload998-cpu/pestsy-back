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
        Schema::create("modules.external_conditions", function (Blueprint $table) {
            $table->id();
            $table->string('obsolete_machinery')->nullable();
            $table->string('sewer_system')->nullable();
            $table->string('debris')->nullable();
            $table->string('containers')->nullable();
            $table->string('spotlights')->nullable();
            $table->string('green_areas')->nullable();
            $table->string('waste')->nullable();
            $table->string('nesting')->nullable();
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
        Schema::dropIfExists('modules.external_conditions');
    }
};
