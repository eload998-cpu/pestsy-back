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
        Schema::table('modules.lamps', function (Blueprint $table) {
            $table->bigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on("modules.locations")->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules.lamps', function (Blueprint $table) {
            $table->dropColumn('location_id');

        });
    }
};
