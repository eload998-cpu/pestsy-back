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
        Schema::table('modules.fumigations', function (Blueprint $table) {
            $table->string('infestation_level')->nullable();

        });

        Schema::table('modules.lamps', function (Blueprint $table) {
            $table->string('infestation_level')->nullable();

        });

        Schema::table('modules.traps', function (Blueprint $table) {
            $table->string('infestation_level')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules.fumigations', function (Blueprint $table) {
            $table->dropColumn('infestation_level');
        });

        Schema::table('modules.lamps', function (Blueprint $table) {
            $table->dropColumn('infestation_level');
        });

        Schema::table('modules.traps', function (Blueprint $table) {
            $table->dropColumn('infestation_level');
        });
    }
};
